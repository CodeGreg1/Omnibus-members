<?php

namespace Modules\Settings\Http\Controllers\Web\Admin;

use Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Spatie\Permission\Models\Role;
use Modules\Base\Support\DateFormat;
use Modules\Base\Support\TimeFormat;
use Illuminate\Support\Facades\Storage;
use Modules\Base\Support\TimezoneValue;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Contracts\Support\Renderable;
use Modules\Settings\Events\SettingsUpdated;
use Modules\Settings\Support\SettingRedirect;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Languages\Repositories\LanguagesRepository;
use Modules\Settings\Http\Requests\AdminStoreSettingRequest;
use Modules\Settings\Http\Requests\AdminUpdateSettingRequest;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository as CurrenciesRepository;

class SettingsController extends BaseController
{   
    /**
     * @var LanguagesRepository $languages
     */
    protected $languages;

    /**
     * @var CurrenciesRepository $currencies
     */
    protected $currencies;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/settings';

    /**
     * @param LanguagesRepository languages
     * @param CurrenciesRepository languages
     */
    public function __construct(
        LanguagesRepository $languages,
        CurrenciesRepository $currencies
    ) 
    {
        $this->languages = $languages;
        $this->currencies = $currencies;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.settings.index');

        // update the redirect url settings domain
        SettingRedirect::update();

        return view('settings::admin.index', [
            'pageTitle' => __('General Settings'),
            'user' => auth()->user(),
            'languages' => $this->languages->all(),
            'timezones' => Timezone::getTimezones(),
            'dateFormats' => DateFormat::lists(),
            'timeFormats' => TimeFormat::lists(),
            'currencies' => $this->currencies->getActive()
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function authentication()
    {
        $this->authorize('admin.settings.index');

        return view('settings::admin.auth', [
            'pageTitle' => __('Authentication Settings'),
            'user' => auth()->user(),
            'languages' => $this->languages->all(),
            'timezones' => Timezone::getTimezones(),
            'dateFormats' => DateFormat::lists(),
            'timeFormats' => TimeFormat::lists(),
            'currencies' => $this->currencies->getActive()
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function registration()
    {
        $this->authorize('admin.settings.index');

        return view('settings::admin.registration', [
            'pageTitle' => __('Registration Settings'),
            'user' => auth()->user(),
            'roles' => Role::all(),
            'languages' => $this->languages->all(),
            'timezones' => Timezone::getTimezones(),
            'dateFormats' => DateFormat::lists(),
            'timeFormats' => TimeFormat::lists(),
            'currencies' => $this->currencies->getActive()
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function recaptcha()
    {
        $this->authorize('admin.settings.index');

        return view('settings::admin.recaptcha', [
            'pageTitle' => __('reCaptcha Settings'),
            'user' => auth()->user(),
            'roles' => Role::all(),
            'languages' => $this->languages->all(),
            'timezones' => Timezone::getTimezones(),
            'dateFormats' => DateFormat::lists(),
            'timeFormats' => TimeFormat::lists(),
            'currencies' => $this->currencies->getActive()
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function googleAnalytics()
    {
        $this->authorize('admin.settings.index');

        return view('settings::admin.google-analytics', [
            'pageTitle' => __('Google analytics Settings')
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function email()
    {
        $this->authorize('admin.settings.index');

        return view('settings::admin.email', [
            'pageTitle' => __('Email Settings'),
            'drivers' => [
                'smtp' => __('SMTP (Recommended)'),
                'ses' => __('SES'),
                'mailgun' => __('Mailgun'),
                'postmark' => __('Postmark'),
                'sendmail' => __('Sendmail - PHP mail()'),
                'log' => __('Log'),
                'array' => __('Array')
            ],
            'encryptions' => [
                'none' => __('None'),
                'ssl' => __('SSL'),
                'tls' => __('TLS')
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function storage()
    {
        $this->authorize('admin.settings.index');

        return view('settings::admin.storage', [
            'pageTitle' => __('Storage Settings')
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function media()
    {
        $this->authorize('admin.settings.index');

        return view('settings::admin.media', [
            'pageTitle' => __('Media Settings')
        ]);
    }

    public function ticket() 
    {
        $this->authorize('admin.settings.index');

        return view('settings::admin.ticket', [
            'pageTitle' => __('Ticket Settings')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param AdminUpdateSettingRequest $request
     * @return JsonResponse
     */
    public function update(AdminUpdateSettingRequest $request)
    {
        foreach ($request->except(['_token', '_method']) as $key => $value) {

            switch ($key) {
                case 'default_profile_photo':
                    if($value = $this->handleFileUpload($request, 'default_profile_photo', 'users')) {
                        Setting::set($key, $value);
                    }

                    break;
                case 'colored_logo':
                    if($value = $this->handleFileUpload($request, 'colored_logo', 'logo')) {
                        Setting::set($key, $value);
                    }

                    break;
                case 'white_logo':
                    if($value = $this->handleFileUpload($request, 'white_logo', 'logo')) {
                        Setting::set($key, $value);
                    }

                    break;
                case 'favicon':
                    if ($value = $this->handleFileUpload($request, 'favicon', 'logo')) {
                        Setting::set($key, $value);
                    }

                    break;
                case 'paypal_logo':
                    if ($value = $this->handleFileUpload($request, 'paypal_logo', 'logo')) {
                        Setting::set($key, $value);
                    }

                    break;
                default:
                    if($key == 'timezone') {
                        $timezone = (new TimezoneValue)->get($value);

                        Setting::set($key, $timezone);
                        Setting::set($key . '_key_value', $value);
                    } else {
                        // Skip if value contains 5 stars straight...
                        if( ! Str::contains($value, '*****') ) {
                            Setting::set($key, $value);
                        }
                    }

                    break;
            }
        }

        Setting::save();

        event(new SettingsUpdated('general settings'));

        return $this->successResponse(__('Setting updated successfully.'));
    }

    /**
     * Handle file upload
     * @param Request $request
     * @param string $key
     * @param string $folder
     * @return String
     */
    protected function handleFileUpload(Request $request, $key, $folder) 
    {
        if($request->has($key)) {

            // if setting has a value then delete the file
            if(setting($key)) {
                $src = setting($key);
                if ($src) {
                    $path = Str::replace(url('storage') . '/', '', $src);
                    Storage::disk('public')->delete($path);
                }
            }

            $path = $request->file($key)[0]->store(
                $folder,
                'public'
            );

            $value = url('storage/' . $path);

            return $value;
        }
    }
}
