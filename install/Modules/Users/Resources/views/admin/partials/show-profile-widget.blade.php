<div class="card profile-widget admin-view-user">
    <div class="profile-widget-header">
        <img alt="image" src="{{ $user->present()->avatar }}" class="rounded-circle profile-widget-picture">
        <div class="profile-widget-items">
            <div class="profile-widget-item">
                <div class="profile-widget-item-label full-name">{{ $user->full_name ?? __('N/A') }}</div>
                <div class="profile-widget-item-value email text-muted">{{ protected_data($user->email, 'email') }}</div>
                <div class="profile-widget-item-value email text-muted user-role">

                </div>
            </div>
        </div>
    </div>
    <div class="profile-widget-description pt-0 pb-0">
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>@lang('Username')</b>
                <span>{{ protected_data($user->username, 'username') ?? __('N/A') }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>@lang('User status')</b>
                <span>{!! \Modules\Users\Support\UserStatus::view($user) !!}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>@lang('Roles')</b>
                @foreach($user->roles as $role)
                    <span class="badge badge-secondary">{{ $role->name }}</span>
                @endforeach
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>@lang('Joined')</b>
                <span>{{ $user->created_at_for_humans }}</span>
            </li>
        </ul>

        @if (setting('allow_wallet') === 'enable')
            <div class="mt-4 mb-2 d-flex justify-content-between">
                <strong>@lang('Balance')</strong>
                <button type="button" class="btn btn-primary btn-sm btn-icon" data-id="{{ $user->id }}" data-toggle="modal" data-target="#user-add-balance-admin-modal">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <ul class="list-group">
                @forelse ($user->getWallets() as $wallet)
                    <li class="list-group-item d-flex justify-content-between align-items-center pr-0">
                        <b class="mr-2">{{ $wallet->currency }}</b>
                        <div class="d-flex align-items-center">
                            <span>{{ currency_format($wallet->balance, $wallet->currency) }}</span>
                            <button
                                type="button"
                                class="btn btn-icon btn-admin-deduct-wallet-balance {{ !$wallet->balance ? 'disabled' : '' }}"
                                data-id="{{ $user->id }}"
                                data-toggle="tooltip"
                                data-title="Deduct balance"
                                data-original-title=""
                                title=""
                                data-currency="{{ $wallet->currency }}"
                                data-balance="{{ currency_format($wallet->balance, $wallet->currency) }}"
                                @disabled(!$wallet->balance)
                            >
                                <i class="fas fa-minus text-danger"></i>
                            </button>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <b class="mr-2">{{ setting('currency') }}</b>
                        <div class="d-flex align-items-center">
                            <span>{{ currency_format(0, setting('currency')) }}</span>
                        </div>
                    </li>
                @endforelse
            </ul>
        @endif
    </div>
    <div class="card-footer text-center">
        <div class="show-user-actions">
            @if(!is_null($user->email_verified_at))
                @if($user->status == \Modules\Users\Support\UserStatus::ACTIVE)
                    <button type="button" class="btn btn-danger btn-block btn-ban-user" data-id="{{ $user->id }}"><i class="fas fa-user-alt-slash"></i> @lang('Ban User')</button>

                    @if(auth()->user()->canImpersonate() && $user->canBeImpersonated())
                        <a href="{{ route('admin.users.impersonate', $user->id) }}" class="btn btn-block btn-warning"><i class="fas fa-user-secret"></i> @lang('Impersonate User')</a>
                    @endif
                @else
                    <button type="button" class="btn btn-success btn-block btn-enable-user" data-id="{{ $user->id }}"><i class="fas fa-user-check"></i> @lang('Enable User')</button>
                @endif
            @else
                <button type="button" class="btn btn-warning btn-block btn-confirm-user" data-id="{{ $user->id }}"><i class="fas fa-user-plus"></i> @lang('Confirm User')</button>
            @endif
            <a href="{{ route('admin.users.index') }}" class="btn btn-block btn-primary"><i class="fas fa-list"></i> @lang('View All')</a>
        </div>
    </div>
</div>

@if (Module::has('Subscriptions') && setting('allow_subscriptions') === 'enable' && isset($subscription))
    @include('subscriptions::admin.user.package-widget')
@endif
