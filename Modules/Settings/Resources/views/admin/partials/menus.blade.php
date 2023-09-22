<div class="card">
  <div class="card-body">
      <ul class="nav nav-pills flex-column">
        <li class="nav-item"><a href="{{ route('admin.settings.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.index' ? ' active' : '' }}">@lang('General')</a></li>
        <li class="nav-item"><a href="{{ route('admin.settings.authentication') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.authentication' ? ' active' : '' }}">@lang('Authentication')</a></li>
        <li class="nav-item"><a href="{{ route('admin.settings.registration') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.registration' ? ' active' : '' }}">@lang('Registration')</a></li>
        <li class="nav-item"><a href="{{ route('admin.settings.recaptcha') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.recaptcha' ? ' active' : '' }}">@lang('reCaptcha')</a></li>
        <li class="nav-item"><a href="{{ route('admin.settings.google-analytics') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.google-analytics' ? ' active' : '' }}">@lang('Google analytics')</a></li>
        <li class="nav-item"><a href="{{ route('admin.settings.email') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.email' ? ' active' : '' }}">@lang('Mail')</a></li>
        <li class="nav-item"><a href="{{ route('admin.settings.storage') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.storage' ? ' active' : '' }}">@lang('Storage')</a></li>
        <li class="nav-item"><a href="{{ route('admin.settings.media') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.media' ? ' active' : '' }}">@lang('Media')</a></li>
	@if (Module::has('Cashier') && Module::isEnabled('Cashier'))
          <li class="nav-item"><a href="{{ route('admin.settings.payment-gateways') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.payment-gateways' ? ' active' : '' }}">@lang('Payment gateways')</a></li>
        @endif
        @if (Module::has('Subscriptions') && Module::isEnabled('Subscriptions'))
          <li class="nav-item"><a href="{{ route('admin.settings.subscriptions') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.subscriptions' ? ' active' : '' }}">@lang('Subscriptions')</a></li>
        @endif
        @if (Module::has('Wallet')  && Module::isEnabled('Wallet'))
          <li class="nav-item"><a href="{{ route('admin.settings.wallet') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.wallet' ? ' active' : '' }}">@lang('Wallet')</a></li>
        @endif
        @if (Module::has('Affiliates') && Module::isEnabled('Affiliates'))
        <li class="nav-item"><a href="{{ route('admin.settings.affiliates.index') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.affiliates.index' ? ' active' : '' }}">@lang('Affiliate')</a></li>
        @endif
	@if (Module::has('Tickets') && Module::isEnabled('Tickets'))
          <li class="nav-item"><a href="{{ route('admin.settings.ticket') }}" class="nav-link{{ Route::currentRouteName() == 'admin.settings.ticket' ? ' active' : '' }}">@lang('Ticket')</a></li>
        @endif
      </ul>
  </div>
</div>
