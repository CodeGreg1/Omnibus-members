<ul class="nav nav-pills mb-4 user-menu">
    @if (Module::has('Wallet') && auth()->user()->hasPermissionTo('user.profile.wallet.index') && setting('allow_wallet') === 'enable')
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'admin.users.overview' ? 'active' : '' }}" href="{{ route('admin.users.overview', $user->id) }}"><i class="fas fa-user-clock"></i> @lang('User Overview')</a>
    </li>
    @endif
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'admin.users.show' ? 'active' : '' }}" href="{{ route('admin.users.show', $user->id) }}"><i class="fas fa-user-clock"></i> @lang('User Activities')</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'admin.users.edit' ? 'active' : '' }}" href="{{ route('admin.users.edit', $user->id) }}"><i class="fas fa-user-edit"></i> @lang('User Information')</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'admin.users.edit-user-settings' ? 'active' : '' }}" href="{{ route('admin.users.edit-user-settings', $user->id) }}"><i class="fas fa-user-cog"></i> @lang('User Settings')</a>
    </li>
    @if (Module::has('Subscriptions') && Module::isEnabled('Subscriptions') && setting('allow_subscriptions') === 'enable' && $user->hasAnySubscription())
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'admin.users.billing' ? 'active' : '' }}" href="{{ route('admin.users.billing', $user->id) }}"><i class="fas fa-money-bill"></i> @lang('Billing & Plan')</a>
        </li>
    @endif
</ul>