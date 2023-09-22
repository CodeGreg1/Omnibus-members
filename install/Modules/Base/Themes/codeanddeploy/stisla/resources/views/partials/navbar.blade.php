<!-- Start Navbar Section -->
<div class="navbar-bg"></div>
<div class="sidebar-brand">
    <a href="{{ route('dashboard.index') }}">
      <img src="{{ setting('white_logo') }}" class="sidebar-brand-logo" alt="{{ setting('app_name') }}">
    </a>
</div>
<div class="sidebar-brand sidebar-brand-sm">
    <a href="{{ route('dashboard.index') }}">{{ substr(setting('app_name'), 0, 2) }}</a>
</div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        @auth
        <li>
          <a href="/" class="nav-link nav-link-lg" data-toggle="tooltip" target="_blank" data-title="@lang('View website')">
              <i class="fas fa-globe"></i>
          </a>
        </li>
        <li class="dropdown">
          <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
          <img alt="image" src="{{ auth()->user()->present()->avatar }}" class="rounded-circle mr-1 profile-avatar">
          <div class="d-sm-none d-lg-inline-block"><span class="profile-first-name">{{ auth()->user()->first_name }}</span> <span class="profile-last-name">{{ auth()->user()->last_name }}</span></div></a>
          <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ route('profile.index') }}" class="dropdown-item has-icon">
              <i class="far fa-user"></i> @lang('Profile')
            </a>
            <a href="{{ route('profile.security') }}" class="dropdown-item has-icon">
              <i class="fas fa-shield-alt"></i> @lang('Security')
            </a>
            @if (setting('allow_subscriptions') === 'enable' && auth()->user()->hasPermissionTo('profile.billing'))
            <a href="{{ route('profile.billing') }}" class="dropdown-item has-icon">
              <i class="fas fa-credit-card"></i> @lang('Billing')
            </a>
            @endif
            @if (Module::has('Wallet') && auth()->user()->hasPermissionTo('user.profile.wallet.index') && setting('allow_wallet') === 'enable')
            <a href="{{ route('user.profile.wallet.index') }}" class="dropdown-item has-icon">
              <i class="fas fa-wallet"></i> @lang('My Wallet')
            </a>
            @endif
            @if (Module::has('Affiliates') && setting('allow_affiliates') === 'enable')
            <a href="{{ route('user.affiliates.index') }}" class="dropdown-item has-icon">
              <i class="fas fa-handshake"></i> @lang('My Affiliate')
            </a>
            @endif
            <a href="{{ route('user.activities.index') }}" class="dropdown-item has-icon">
              <i class="fas fa-bolt"></i> @lang('Activities')
            </a>
            <a href="{{ route('profile.personal-access-tokens') }}" class="dropdown-item has-icon">
              <i class="fas fa-check-double"></i> @lang('Personal Access Token')
            </a>
            <div class="dropdown-divider"></div>
            @if(session('impersonated_by'))
              <a href="{{ route('admin.users.leave-impersonate') }}" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> @lang('Leave Impersonation')
              </a>
            @else
              <a href="{{ route('auth.logout.perform') }}" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> @lang('Logout')
              </a>
            @endif

          </div>
        </li>
        @endauth
    </ul>
</nav>
<!-- End Navbar Section -->