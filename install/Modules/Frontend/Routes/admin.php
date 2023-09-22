<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin/frontends', 'as' => 'admin.frontends.', 'namespace' => 'Admin'], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('/settings', 'GeneralSettingController@index')
            ->name('settings.index');
        Route::post('/settings', 'GeneralSettingController@update')
            ->name('settings.update');

        Route::get('/settings/logos', 'LogoSettingController@index')
            ->name('settings.logos.index');
        Route::post('/settings/logos', 'LogoSettingController@update')
            ->name('settings.logos.update');

        Route::get('/settings/contact-details', 'ContactDetailSettingController@index')
            ->name('settings.contact-details.index');
        Route::post('/settings/contact-details', 'ContactDetailSettingController@update')
            ->name('settings.contact-details.update');

        Route::get('/settings/legal-pages', 'LegalPageSettingController@index')
            ->name('settings.legal-pages.index');
        Route::post('/settings/legal-pages', 'LegalPageSettingController@update')
            ->name('settings.legal-pages.update');

        Route::get('/settings/social-links', 'SocialLinkSettingController@index')
            ->name('settings.social-links.index');
        Route::post('/settings/social-links', 'SocialLinkSettingController@update')
            ->name('settings.social-links.update');

        Route::get('/settings/login-register', 'LoginRegisterSettingController@index')
            ->name('settings.login-register.index');
        Route::post('/settings/login-register', 'LoginRegisterSettingController@update')
            ->name('settings.login-register.update');

        Route::get('/settings/theme-colors', 'ThemeColorsSettingController@index')
            ->name('settings.theme-colors.index');
        Route::post('/settings/theme-colors', 'ThemeColorsSettingController@update')
            ->name('settings.theme-colors.update');

        Route::get('/settings/navbar-section', 'NavbarSectionSettingController@index')
            ->name('settings.navbar-section.index');
        Route::post('/settings/navbar-section', 'NavbarSectionSettingController@update')
            ->name('settings.navbar-section.update');

        Route::get('/settings/breadcrumb-section', 'BreadcrumbSectionSettingController@index')
            ->name('settings.breadcrumb-section.index');
        Route::post('/settings/breadcrumb-section', 'BreadcrumbSectionSettingController@update')
            ->name('settings.breadcrumb-section.update');

        Route::get('/settings/footer-section', 'FooterSectionSettingController@index')
            ->name('settings.footer-section.index');
        Route::post('/settings/footer-section', 'FooterSectionSettingController@update')
            ->name('settings.footer-section.update');

        Route::get('/settings/cookie', 'CookieSettingController@index')
            ->name('settings.cookie.index');
        Route::post('/settings/cookie', 'CookieSettingController@update')
            ->name('settings.cookie.update');

        Route::get('/settings/custom-css-js', 'CustomCssAndJsSettingController@index')
            ->name('settings.custom-css-js.index');
        Route::post('/settings/custom-css-js', 'CustomCssAndJsSettingController@update')
            ->name('settings.custom-css-js.update');
    });
});
