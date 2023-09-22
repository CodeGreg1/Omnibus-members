<?php

namespace Modules\Install\Http\Controllers\Web;

use Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Base\Support\JsPolicy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Support\Renderable;
use Modules\Install\Repositories\InstallsRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;

class InstallController extends BaseController
{   
    /**
     * @var InstallsRepository $install
     */
    protected $install;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/install';

    /**
     * @var InstallsRepository $install
     */
    public function __construct(InstallsRepository $install) 
    {
        $this->install = $install;

        parent::__construct();
    }

    /**
     * Index installer
     * @return Renderable
     */
    public function index()
    {
        Artisan::call('cache:clear');
        Artisan::call('storage:link');

        return view('install::index', [
            'pageTitle' => __('Install')
        ]);
    }

    /**
     * System requirement checker
     * @return Renderable
     */
    public function requirements() 
    {
        $requirements = $this->getRequirements();

        return view('install::requirements', [
            'pageTitle' => __('System Requirements'),
            'requirements' => $requirements
        ]);
    }

    /**
     * System permissions checker
     * @return Renderable
     */
    public function permissions() 
    {
        $permissions = $this->getPermissions();

        return view('install::permissions', [
            'pageTitle' => __('Permissions'),
            'permissions' => $permissions
        ]);
    }

    /**
     * System database checker
     * @return Renderable
     */
    public function database() 
    {
        $permissions = $this->getPermissions();

        return view('install::database', [
            'pageTitle' => __('Database Info'),
            'permissions' => $permissions
        ]);
    }

     /**
     * System database checker
     * 
     * @param Request $request
     * 
     * @return Renderable
     */
    public function startInstallation(Request $request) 
    {
        if (!$this->isAllRequirementsInstalled()) {
            return redirect()->route('install.requirements');
        }

        if (!$this->isPermissionsGranted()) {
            return redirect()->route('install.permissions');
        }

        $dbCredentials = $request->only('host', 'username', 'password', 'database');

        if(!$this->isDbCredentialsValid($dbCredentials)) {
            return redirect()->route('install.database')
                ->withInput(
                    Arr::except($dbCredentials, 'password')
                )
                ->withErrors(
                    __('Database connection to your database cannot established.
                Please provide the correct database credentials.')
                );
        }

        Session::put('install-db-credentials', $dbCredentials);

        return view('install::installation', [
            'pageTitle' => 'Installation'
        ]);
    }

    /**
     * Start the installation
     * 
     * @param Request $request
     * 
     * @return Renderable
     */
    public function install(Request $request) 
    {
        try {
            ini_set('memory_limit', '1000M');
            set_time_limit(2000);

            enable_wallet_settings(); //enable wallet in settings
            enable_subscription_settings(); //enable subscription in settings
            enable_affiliate(); // enable affiliate settings
            $this->setDefaultPaymentGatewaySettings(); // set default settings for payment gateway
            $this->setDefaultFrontendThemeColorSettings(); // Set default settings for frontend theme colors

            $appURL = request()->getSchemeAndHttpHost();
            $db = Session::get('install-db-credentials');

            copy(base_path('.env.example'), base_path('.env'));

            // reload evironment variables
            (new LoadEnvironmentVariables)->bootstrap(app());

            $path = base_path('.env');
            $env = file_get_contents($path);

            $env = str_replace('APP_NAME='.env('APP_NAME'), sprintf('APP_NAME="%s"', $request->get('app_name')), $env);
            $env = str_replace('APP_URL='.env('APP_URL'), sprintf('APP_URL="%s"', $appURL), $env);
            $env = str_replace('DB_HOST='.env('DB_HOST'), sprintf('DB_HOST="%s"', $db['host']), $env);
            $env = str_replace('DB_DATABASE='.env('DB_DATABASE'), sprintf('DB_DATABASE="%s"', $db['database']), $env);
            $env = str_replace('DB_USERNAME='.env('DB_USERNAME'), sprintf('DB_USERNAME="%s"', $db['username']), $env);
            $env = str_replace('DB_PASSWORD='.env('DB_PASSWORD'), sprintf('DB_PASSWORD="%s"', $db['password']), $env);

            file_put_contents($path, $env);

            $this->setDatabaseCredentials($db);
            config(['app.debug' => true]);

            Setting::set('frontend_dark_logo', '/upload/logos/frontend-logo-black.png');
            Setting::set('frontend_white_logo', '/upload/logos/frontend-logo-white.png');
            Setting::set('default_profile_photo', '/users/avatar.png');
            Setting::set('services_facebook_redirect', $appURL . url_remove_domain(setting('services_facebook_redirect')));
            Setting::set('services_google_redirect', $appURL . url_remove_domain(setting('services_google_redirect')));
            Setting::set('ticket_hours_before_auto_closed', 72);
            Setting::set('date_format', 'M j, Y h:i A');
            Setting::set('app_name', $request->get('app_name'));
            Setting::save();

            Artisan::call('key:generate', ['--force' => true]);
            Artisan::call('migrate');
            Artisan::call('db:seed');
            Artisan::call('optimize:clear');

            set_time_limit(300);
            ini_set('memory_limit', '512M');

            return redirect()->route('install.success');
        } catch (\Exception $e) {
            @unlink(base_path('.env'));
            $this->dropTables();
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->route('install.error');
        }
    }

    /**
     * Successfully installed
     * 
     * @return Renderable
     */
    public function success() 
    {
        return view('install::success', [
            'pageTitle' => __('Installed successfully')
        ]);
    }

    /**
     * Error on installation
     * 
     * @return Renderable
     */
    public function error() 
    {
        return view('install::error', [
            'pageTitle' => __('Whoops!')
        ]);
    }


    /**
     * Check if database credentials is valid
     * 
     * @param array $credentials
     * 
     * @return boolean
     */
    protected function isDbCredentialsValid($credentials)
    {
        $this->setDatabaseCredentials($credentials);

        try {
            DB::statement("SHOW TABLES");
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Check all requirements extensions installed
     * 
     * @return boolean
     */
    protected function isAllRequirementsInstalled() 
    {
        $result = true;

        foreach($this->getRequirements() as $requirement) {
            if($requirement == false) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Check if permission is granted
     * 
     * @return boolean
     */
    protected function isPermissionsGranted() 
    {
        $result = true;

        foreach($this->getPermissions() as $permission) {
            if($permission['is_writable'] == false) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Get the requirements
     * 
     * @return array
     */
    protected function getRequirements() 
    {
        return [
            'PHP Version (>= 8.1)' => version_compare(phpversion(), '8.1', '>='),
            'BCMath Extension' => extension_loaded('bcmath'),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'PDO Extension' => extension_loaded('PDO'),
            'PDO MySQL Extension' => extension_loaded('pdo_mysql'),
            'Mbstring Extension' => extension_loaded('mbstring'),
            'Tokenizer Extension' => extension_loaded('tokenizer'),
            'XML Extension' => extension_loaded('xml'),
            'Ctype PHP Extension' => extension_loaded('ctype'),
            'JSON PHP Extension' => extension_loaded('json'),
            'GD Extension' => extension_loaded('gd'),
            'Fileinfo Extension' => extension_loaded('fileinfo'),
            'Intl Extension' => extension_loaded('intl')
        ];
    }

    /**
     * Get the permissions
     * 
     * @return array
     */
    protected function getPermissions() 
    {
        return [
            'public/upload/media' => [
                'is_writable' => is_writable(public_path('upload/media')),
                'permission' => fileperms(public_path('upload/media'))
            ],
            'public/upload/users' => [
                'is_writable' => is_writable(public_path('upload/users')),
                'permission' => fileperms(public_path('upload/users'))
            ],
            'storage/app' => [
                'is_writable' => is_writable(storage_path('app')),
                'permission' => fileperms(storage_path('app'))
            ],
            'storage/framework/cache' => [
                'is_writable' => is_writable(storage_path('framework/cache')),
                'permission' => fileperms(storage_path('framework/cache'))
            ],
            'storage/framework/sessions' => [
                'is_writable' => is_writable(storage_path('framework/sessions')),
                'permission' => fileperms(storage_path('framework/sessions'))
            ],
            'storage/framework/views' => [
                'is_writable' => is_writable(storage_path('framework/views')),
                'permission' => fileperms(storage_path('framework/views'))
            ],
            'storage/logs' => [
                'is_writable' => is_writable(storage_path('logs')),
                'permission' => fileperms(storage_path('logs'))
            ],
            'bootstrap/cache' => [
                'is_writable' => is_writable(base_path('bootstrap/cache')),
                'permission' => fileperms(base_path('bootstrap/cache'))
            ],
            'Base Directory' => [
                'is_writable' => is_writable(base_path('')),
                'permission' => fileperms(base_path('')),
            ]
        ];
    }

    /**
     * Set database credentials
     * 
     * @param array $credentials
     * @return void
     */
    protected function setDatabaseCredentials($credentials)
    {
        $default = config('database.default');

        DB::disconnect('mysql');

        config([
            'database.connections.' . $default . '.host' => $credentials['host'],
            'database.connections.' . $default . '.database' => $credentials['database'],
            'database.connections.' . $default . '.username' => $credentials['username'],
            'database.connections.' . $default . '.password' => $credentials['password']
        ]);
    }

    /**
     *  Drop database tables
     * 
     * @return void
     */
    protected function dropTables() 
    {
        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);

        Schema::disableForeignKeyConstraints();
        foreach($tables as $table) {
            Schema::dropIfExists($table);
        }
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Set default frontend theme color settings
     * 
     * @return void
     */
    protected function setDefaultFrontendThemeColorSettings() 
    {
        setting([
            // Theme state colors
            'frontend_color_scheme' => 'light',
            'frontend_primary_color' => '#0084ff',
            'frontend_secondary_color' => '#6c757d',
            'frontend_info_color' => '#0dcaf0',
            'frontend_success_color' => '#10b981',
            'frontend_warning_color' => '#ffc107',
            'frontend_danger_color' => '#f65660',

            // Navbar
            'frontend_navbar_bg_color' => '#25272f',
            'frontend_navbar_menu_toggler_icon_color' => '#ffffff',
            'frontend_navbar_menu_text_color' => '#ffffff',
            'frontend_navbar_menu_text_hover_color' => '#0084ff',
            'frontend_navbar_menu_text_active_color' => '#0084ff',

            // Breadcrumb
            'frontend_breadcrumb_bg_color' => '#f9f9f9',
            'frontend_breadcrumb_text_color' => '#051441',
            'frontend_breadcrumb_text_hover_color' => '#0084ff',
            'frontend_breadcrumb_page_title_color' => '#051441',

            // Footer 
            'frontend_footer_bg_color' => '#25272f',
            'frontend_footer_heading_color' => '#ffffff',
            'frontend_footer_text_color' => '#ffffff',
            'frontend_footer_about_us' => 'We crafted it to make it faster the Laravel Application development and Save thousands of money on developing it from scratch.',
            'frontend_footer_copyright_text' => 'KoolMembership &copy; 2023. All Rights Reserved.',

            // Legal
            'frontend_page_terms' => 'terms-and-conditions',
            'frontend_page_policy' => 'privacy-policy'
        ])->save();
    }

    /**
     * Set default payment gateway settings
     * 
     * @return void
     */
    protected function setDefaultPaymentGatewaySettings() 
    {
        setting([
            // PayPal Gateway
            'paypal_display_name' => 'PayPal',
            'paypal_logo' => '/upload/logos/paypal.png',
            // Stripe Gateway
            'stripe_display_name' => 'Stripe',
            'stripe_logo' => '/upload/logos/stripe.png',
            // Razorpay Gateway
            'razorpay_display_name' => 'Razorpay',
            'razorpay_logo' => '/upload/logos/razorpay.png',
            // Razorpay Gateway
            'mollie_display_name' => 'Mollie',
            'mollie_logo' => '/upload/logos/mollie.png',
        ])->save();
    }
}
