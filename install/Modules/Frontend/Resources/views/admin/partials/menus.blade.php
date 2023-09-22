<div class="card">
	<div class="card-body">
		<ul class="nav nav-pills flex-column">
			<li class="nav-item"><a href="{{ route('admin.frontends.settings.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.frontends.settings.index' ? ' active' : '' }}">@lang('General')</a></li>
			<li class="nav-item"><a href="{{ route('admin.frontends.settings.logos.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.frontends.settings.logos.index' ? ' active' : '' }}">@lang('Logos')</a></li>
			<li class="nav-item"><a href="{{ route('admin.frontends.settings.contact-details.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.frontends.settings.contact-details.index' ? ' active' : '' }}">@lang('Contact details')</a></li>
			<li class="nav-item"><a href="{{ route('admin.frontends.settings.legal-pages.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.frontends.settings.legal-pages.index' ? ' active' : '' }}">@lang('Legal pages')</a></li>
			<li class="nav-item"><a href="{{ route('admin.frontends.settings.login-register.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.frontends.settings.login-register.index' ? ' active' : '' }}">@lang('Login & Register')</a></li>
			<li class="nav-item"><a href="{{ route('admin.frontends.settings.social-links.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.frontends.settings.social-links.index' ? ' active' : '' }}">@lang('Social Links')</a></li>
			<li class="nav-item"><a href="{{ route('admin.frontends.settings.theme-colors.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.frontends.settings.theme-colors.index' ? ' active' : '' }}">@lang('Theme colors')</a></li>
			<li class="nav-item"><a href="{{ route('admin.frontends.settings.navbar-section.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.frontends.settings.navbar-section.index' ? ' active' : '' }}">@lang('Navbar section')</a></li>
			<li class="nav-item"><a href="{{ route('admin.frontends.settings.breadcrumb-section.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.frontends.settings.breadcrumb-section.index' ? ' active' : '' }}">@lang('Breadcrumb section')</a></li>
			<li class="nav-item"><a href="{{ route('admin.frontends.settings.footer-section.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.frontends.settings.footer-section.index' ? ' active' : '' }}">@lang('Footer section')</a></li>
			<li class="nav-item"><a href="{{ route('admin.frontends.settings.cookie.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.frontends.settings.cookie.index' ? ' active' : '' }}">@lang('GDPR Cookie')</a></li>
			<li class="nav-item"><a href="{{ route('admin.frontends.settings.custom-css-js.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.frontends.settings.custom-css-js.index' ? ' active' : '' }}">@lang('Custom CSS&JS')</a></li>
		</ul>
	</div>
</div>
