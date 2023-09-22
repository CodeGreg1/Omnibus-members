<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Base\Support\DateFormat;
use Modules\Base\Support\TimeFormat;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Base\Repositories\CountryRepository;
use Modules\Profile\Models\Session as SessionModel;
use Modules\Profile\Repositories\CompanyRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Languages\Repositories\LanguagesRepository;
use Modules\Profile\Repositories\ProfileTwoFactorRepository;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository as CurrenciesRepository;

class ProfileController extends BaseController
{

    /**
     * @var UserRepository $users
     */
    protected $users;

    /**
     * @var CountryRepository $countries
     */
    protected $countries;

    /**
     * @var CompanyRepository $companies
     */
    protected $companies;

    /**
     * @var CurrenciesRepository $currencies
     */
    protected $currencies;

    /**
     * @var LanguagesRepository $currencies
     */
    protected $languages;

    /**
     * @param UserRepository $users
     * @param CountryRepository $countries
     * @param CompanyRepository $companies
     * @param CurrenciesRepository $currencies
     * @param LanguagesRepository $languages
     */
    public function __construct(
        UserRepository $users, 
        CountryRepository $countries, 
        CompanyRepository $companies,
        CurrenciesRepository $currencies,
        LanguagesRepository $languages
    ) {
        $this->users = $users;
        $this->countries = $countries;
        $this->companies = $companies;
        $this->currencies = $currencies;
        $this->languages = $languages;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('profile.index');

        $company = $this->companies->getByUserIdWithAddress(auth()->id());

        return view('profile::index', [
            'pageTitle' => __('My profile'),
            'user' => auth()->user(),
            'countries' => $this->countries->all(),
            'company' => $company,
            'address' => auth()->user()->addresses->first(),
            'timezones' => $this->handleTimezoneResponse(),
            'dateFormat' => $this->handleDateFormatResponse(),
            'timeFormat' => $this->handleTimeFormatResponse(),
            'languages' => $this->handleLanguageResponse(),
            'currencies' => $this->handleCurrencyResponse()
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function security()
    {  
        $this->authorize('profile.security');

        return view('profile::security', [
            'pageTitle' => __('Account security'),
            'sessions' => auth()->user()->sessions,
            'appName' => config('app.name')
        ]);
    }

    /**
     * Handle currency response
     * 
     * @return Model
     */
    protected function handleCurrencyResponse() 
    {
        return $this->currencies->getActive();
    }

    /**
     * Handle language response
     * 
     * @return Model
     */
    protected function handleLanguageResponse() 
    {
        return $this->languages->all();
    }

    /**
     * Handle time format response
     * 
     * @return array
     */
    protected function handleTimeFormatResponse() 
    {
        return TimeFormat::lists();
    }

    /**
     * Handle date format response
     * 
     * @return array
     */
    protected function handleDateFormatResponse() 
    {
        return DateFormat::lists();
    }

    /**
     * Handle timezone response
     * 
     * @return array
     */
    protected function handleTimezoneResponse() 
    {
        return Timezone::getTimezones();
    }
}
