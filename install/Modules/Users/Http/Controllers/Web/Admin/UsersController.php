<?php

namespace Modules\Users\Http\Controllers\Web\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Spatie\Permission\Models\Role;
use Modules\Base\Support\DateFormat;
use Modules\Base\Support\TimeFormat;
use Modules\Users\Events\UserBanned;
use Illuminate\Http\RedirectResponse;
use Modules\Users\Events\UserCreated;
use Modules\Users\Events\UserDeleted;
use Modules\Users\Events\UserEnabled;
use Modules\Users\Events\UserUpdated;
use Modules\Users\Support\UserStatus;
use Illuminate\Support\Facades\Session;
use Modules\Users\Events\UserConfirmed;
use Camroncade\Timezone\Facades\Timezone;
use Modules\Users\Events\UserImpersonated;
use Modules\Users\Services\UserInvitation;
use Modules\EmailTemplates\Services\Mailer;
use Illuminate\Contracts\Support\Renderable;
use Modules\Roles\Repositories\RoleRepository;
use Modules\Users\Repositories\UserRepository;
use Modules\Base\Repositories\AddressRepository;
use Modules\Base\Repositories\CountryRepository;
use Modules\Users\Events\UserLeaveImpersonation;
use Modules\Users\Http\Requests\CreateUserRequest;
use Modules\Users\Http\Requests\UpdateUserRequest;
use Modules\Users\Repositories\ActivityRepository;
use Modules\Profile\Repositories\CompanyRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Languages\Repositories\LanguagesRepository;
use Modules\Users\Http\Requests\UpdateUserSettingsRequest;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository as CurrenciesRepository;

class UsersController extends BaseController
{   
    use UserInvitation;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * @var CountryRepository
     */
    protected $countries;

    /**
     * @var CompanyRepository
     */
    protected $companies;

    /**
     * @var AddressRepository
     */
    protected $addresses;

    /**
     * @var CurrenciesRepository
     */
    protected $currencies;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/users';

    /**
     * @param Mailer $mailer
     * @param UserRepository $users
     * @param RoleRepository $roles
     * @param CountryRepository $countries
     * @param CompanyRepository $companies
     * @param AddressRepository $addresses
     * @param LanguagesRepository $languages
     * @param CurrenciesRepository $currencies
     * @param ActivityRepository $activities
     */
    public function __construct(
        Mailer $mailer,
        UserRepository $users, 
        RoleRepository $roles,
        CountryRepository $countries, 
        CompanyRepository $companies, 
        AddressRepository $addresses,
        LanguagesRepository $languages,
        CurrenciesRepository $currencies,
        ActivityRepository $activities) 
    {
        $this->mailer = $mailer;

        $this->users = $users;

        $this->roles = $roles;

        $this->countries = $countries;

        $this->companies = $companies;

        $this->addresses = $addresses;

        $this->languages = $languages;

        $this->currencies = $currencies;

        $this->activities = $activities;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.users.index');

        return view('users::admin.index', [
            'pageTitle' => config('users.name'),
            'policies' => JsPolicy::get('users', '.', [
                'edit-user-settings',
                'billings'
            ])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.users.create');

        return view('users::admin.create', [
            'roles' => Role::all(),
            'pageTitle' => __('Add new user'),
            'userStatuses' => UserStatus::lists(),
            'countries' => $this->countries->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request)
    {   
        $attributes = array_merge($request->userData(), [
            'email_verified_at' => now()
        ]);

        $attributes = array_merge($attributes, ['invited' => 1]);

        $user = $this->users->create($attributes);

        $user->assignRole($request->get('role'));

        if (request('company_name')) {
            $this->handleCreatingCompany($request, $user);
        }

        event(new UserCreated($user));

        return $this->handleCreateResponse($user, $request);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $this->authorize('admin.users.show');

        $company = $this->companies->getByUserIdWithAddress($id);

        return view('users::admin.show', [
            'company' => $company,
            'pageTitle' => __('View user'),
            'user' => $this->users->find($id),
            'activities' => $this->activities->getUserLatest($id),
            'countries' => $this->countries->all(),
            'timezone' => $this->handleTimezoneResponse(),
            'languages' => $this->handleLanguageResponse(),
            'currencies' => $this->handleCurrencyResponse(),
            'dateFormat' => $this->handleDateFormatResponse(),
            'timeFormat' => $this->handleTimeFormatResponse(),
            'company_address' => ! is_null( $company ) 
                ? $company->addresses->first() 
                : null
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.users.edit');

        $user = $this->users->find($id);
        $company = $this->companies->getByUserIdWithAddress($id);

        return view('users::admin.edit', [
            'company' => $company,
            'pageTitle' => __('Edit user information'),
            'user' => $user,
            'countries' => $this->countries->all(),
            'timezones' => $this->handleTimezoneResponse(),
            'languages' => $this->handleLanguageResponse(),
            'currencies' => $this->handleCurrencyResponse(),
            'dateFormat' => $this->handleDateFormatResponse(),
            'timeFormat' => $this->handleTimeFormatResponse(),
            'address' => $user->addresses->first() //just use the user address if no company address
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function editUserSettings($id) 
    {   
        $this->authorize('admin.users.edit-user-settings');

        return view('users::admin.edit-user-settings', [
            'roles' => Role::all(),
            'user' => $this->users->find($id),
            'pageTitle' => __('Edit user settings'),
            'userStatuses' => UserStatus::lists(),
            'userRole' => $this->users->role($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateUserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->handleUpdatingUser($request, $id);

        if( request('company_name') ) {
            $company = $this->handleUpdatingCompany($request, $id);
        }

        if( request('country') ) {
            $address = $this->handleUpdatingAddress($request);
            $address->users()->sync([$id]);
        }

        event(new UserUpdated($user));

        return $this->handleUpdateResponse();
    }

    /**
     * Handle on updating user settings
     * 
     * @param UpdateUserSettingsRequest $request
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function updateUserSettings(UpdateUserSettingsRequest $request, $id) 
    {
        $user = $this->users->find($id);

        $this->users->update($user, $request->data());

        $user->roles()->detach();//remove roles
        
        $user->assignRole($request->get('role'));

        event(new UserUpdated($user));

        return $this->handleUpdateResponse();
    }

    /**
     * Multi delete users
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.users.delete');
        
        $this->users->multiDelete($request->ids);

        event(new UserDeleted());

        return $this->successResponse(__('Selected user(s) deleted successfully.'));
    }

    /**
     * Multi delete users
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function multiBan(Request $request)
    {
        $this->authorize('admin.users.multi-ban');

        $this->users->multiBan($request->ids);

        event(new UserBanned());

        if(count($request->ids) > 1) {
            return $this->successResponse(__('Users banned successfully.'));
        }

        return $this->successResponse(__('User banned successfully.'));
        
    }

    /**
     * Multi enable users
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function multiEnable(Request $request)
    {
        $this->authorize('admin.users.multi-enable');

        $this->users->multiEnable($request->ids);

        event(new UserEnabled());

        return $this->successResponse(__('Selected user(s) enabled successfully.'));
    }

    /**
     * Multi confirm users
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function multiConfirm(Request $request)
    {
        $this->authorize('admin.users.multi-confirm');
        
        $this->users->multiConfirm($request->ids);

        event(new UserConfirmed());

        return $this->successResponse(__('Selected user(s) confirmed successfully.'));
    }

    /**
     * Handle impersonating user
     * 
     * @param int $id
     * 
     * @return RedirectResponse
     */
    public function impersonate($id) 
    {
        $user = $this->users->findOrFail($id);

        // put in the top to fix the log causer_id because the impersonated user is reflected if below
        event(new UserImpersonated($user));

        auth()->user()->impersonate($user);

        return redirect()->route('dashboard.index');
    }

    /**
     * Handle leaving user impersonation
     * 
     * @return RedirectResponse
     */
    public function leaveImpersonate() 
    {
        $user = auth()->user();

        event(new UserLeaveImpersonation($user));

        $user->leaveImpersonation();

        return redirect()->route('dashboard.index'); 
    }

    /**
     * Handle getting all roles
     * 
     * @return RoleRepository
     */
    public function allRoles() 
    {
        return $this->roles->all();
    }

    /**
     * Handle on updating user
     * 
     * @param Request $request
     * @param int $userId
     * 
     * @return UserRepository
     */
    protected function handleUpdatingUser($request, $userId) 
    {
        $user = $this->users->find($userId);
        
        $this->users->update($user, $request->userData());

        $user->refresh();

        return $user;
    }

    /**
     * Handle on updating company
     * 
     * @param Request $request
     * @param int $userId
     * 
     * @return CompanyRepository
     */
    protected function handleUpdatingCompany($request, $userId) 
    {
        return $this->companies->updateOrCreate([
            'user_id' => $userId,
            'id' => $request->get('company_id')
        ], $request->companyData());
    }

    /**
     * Handle on updating company
     * 
     * @param Request $request
     * 
     * @return AddressRepository
     */
    protected function handleUpdatingAddress($request) 
    {
        return $this->addresses->updateOrCreate([
            'id' => $request->get('address_id')
        ], $request->addressData());
    }

    /**
     * Handle update response
     * 
     * @param string $message
     * 
     * @return Response
     */
    protected function handleUpdateResponse($message = 'User updated successfully.') 
    {   
        return $this->handleAjaxRedirectResponse(__($message), $this->redirectTo);
    }

    /**
     * Handle creating company
     * 
     * @param Request $request
     * @param User $user
     * 
     * @return mixed
     */
    protected function handleCreatingCompany(Request $request, $user) 
    {
        $company = $this->companies->create(array_merge($request->companyData(), [
            'user_id' => $user->id
        ]));

        $address = $this->addresses->create($request->addressData());

        $company->addresses()->sync([$address->id]);
    }

    /**
     * Handle create response
     * 
     * @param string $message
     * 
     * @return Response
     */
    protected function handleCreateResponse($user, $request, $message = 'User created successfully.') 
    {   
        if($request->has('send_invitation') && $request->get('send_invitation') == 1) {
            $this->sendUserInvitation($user, $request);
        }
        
        return $this->handleAjaxRedirectResponse(
            __($message), 
            $this->redirectTo
        );
    }

    /**
     * Handle on currency response
     * 
     * @return CurrenciesRepository
     */
    protected function handleCurrencyResponse() 
    {
        return $this->currencies->getActive();
    }

    /**
     * Handle on language response
     * 
     * @return LanguageRepository
     */
    protected function handleLanguageResponse() 
    {
        return $this->languages->all();
    }

    /**
     * Handle on time format response
     * 
     * @return array
     */
    protected function handleTimeFormatResponse() 
    {
        return TimeFormat::lists();
    }

    /**
     * Handle on date format response
     * 
     * @return array
     */
    protected function handleDateFormatResponse() 
    {
        return DateFormat::lists();
    }

    /**
     * Handle on timezone response
     * 
     * @return array
     */
    protected function handleTimezoneResponse() 
    {
        return Timezone::getTimezones();
    }
}
