@extends('subscriptions::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/tom-select/dist/css/tom-select.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="#"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.coupons.index') }}"> @lang('Coupons')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.coupons.show', [$coupon]) }}"> {{ $coupon->name }}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="{{ route('admin.coupons.promo-codes.show', [$coupon, $promoCode]) }}"> {{ $promoCode->code }}</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <div class="d-flex align-items-center justify-content-between mt-4 mt-lg-0">
        <div class="btn-group ml-lg-2 mr-3">
            <a href="#" class="btn btn-icon icon-left btn-success" data-toggle="modal" data-target="#edit-coupon-promo-code-modal">
                <i class="fas fa-pencil-alt"></i>
                @lang('Edit Promo code')
            </a>
        </div>

        @include('cashier::partials.live-mode-status')
    </div>
@endsection

@section('content')
    <section class="section">
        <input type="hidden" id="Users" value="{{ implode(",", $users->pluck('id')->toArray()) }}">
        <div class="section-body">
            <div class="row">
                <div class="col-md-8">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="general-settings-tab" data-toggle="pill" data-target="#general-settings" type="button" role="tab" aria-controls="general-settings" aria-selected="true">@lang('General settings')</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="promo-code-redemptions-user-tab" data-toggle="pill" data-target="#promo-code-redemptions-user" type="button" role="tab" aria-controls="promo-code-redemptions-user" aria-selected="false">@lang('Subscriptions')</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="general-settings" role="tabpanel" aria-labelledby="general-settings-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h6>@lang('Applicable Users')</h6>
                                    <div class="card-header-action ml-auto">
                                        <a href="#" role="button" class="btn btn-primary" data-toggle="modal" data-target="#add-coupon-user-modal">@lang('Add user')</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled list-unstyled-border">
                                    @forelse ($users as $user)
                                        <li class="media">
                                            <a href="#">
                                                <img class="mr-3 rounded" width="40" src="{{  $user->present()->avatar ?? '/upload/users/avatar.png' }}" alt="User">
                                            </a>
                                            <div class="media-body">
                                                <div class="media-right">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a
                                                            class="dropdown-item btn-delete-coupon-user"
                                                            href="javascript:void(0)"
                                                            data-href="{{ route('admin.coupons.promo-codes.user-destroy', [
                                                                $promoCode,
                                                                $user
                                                            ]) }}">
                                                            @lang('Delete')
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="media-title">
                                                    <a href="javascript:void(0)">
                                                        {{ $user->full_name }}
                                                    </a>
                                                </div>
                                                <div class="text-muted text-small">
                                                    {{ $user->email }}
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="media justify-content-center">
                                            <p class="text-center py-4 text-muted">
                                                @lang('This coupon is available to all users')
                                            </p>
                                        </li>
                                    @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="promo-code-redemptions-user" role="tabpanel" aria-labelledby="promo-code-redemptions-user-tab">
                            <div id="promo-code-subscriptions-datatable"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6>@lang('Promo code details')</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Code')</label>
                                    <p class="mb-0">{{ $promoCode->code }}</p>
                                </div>
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Status')</label>
                                    <p class="mb-0 text-{{ $promoCode->active ? 'primary' : 'dark' }}">{{ $promoCode->active ? 'Active' : 'Archived' }}</p>
                                </div>
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Usage')</label>
                                    <p class="mb-0">{{ $promoCode->times_redeemed ? $promoCode->times_redeemed .' '. __('redemptions') : __('No redemptions yet') }}</p>
                                </div>
                                <div class="form-group col-12 mb-0">
                                    <label class="mb-0">@lang('Limit')</label>
                                    @php
                                        $limit = $promoCode->max_redemptions;
                                        if (!$limit) {
                                            $limit = $promoCode->coupon->redeem_limit_count;
                                        }
                                    @endphp
                                    <p class="mb-0">{{ $limit ? $limit . ' ' . __('redemptions only') : __('No limit') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modals')
    @include('carts::admin.modals.add-coupon-user')
    @include('carts::admin.modals.edit-promo-code')
@endsection
