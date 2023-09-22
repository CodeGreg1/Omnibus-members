@extends('settings::layouts.master')

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Settings')</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-3 col-xm-12">
            @include('settings::admin.partials.menus')
        </div>
        <div class="col-lg-9 col-md-9 col-xm-12">
            <form id="admin-affiliate-settings-edit-form" method="post">
                @method('patch')
                <input type="hidden" name="redirect" value="/admin/settings/affiliates">

                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Affiliate settings')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Enable affiliates')
                                </label>
                                <select class="form-control ml-auto" name="allow_affiliates" id="allow_affiliates">
                                    <option value="disable" @selected(setting('allow_affiliates') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_affiliates') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group w-100">
                            <label class="mr-4">
                                @lang('Auto approve affiliate membership on request by users.')
                            </label>
                            <select class="form-control ml-auto" name="auto_approve_affiliate_membership" id="auto_approve_affiliate_membership">
                                <option value="enable" @selected(setting('auto_approve_affiliate_membership', 'disable') === 'enable')>@lang('Enable')</option>
                                <option value="disable" @selected(setting('auto_approve_affiliate_membership', 'disable') === 'disable')>@lang('Disable')</option>
                            </select>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="commision_verification_days_count">@lang('Number of days for commision verification')</label>
                            <input type="number" id="commision_verification_days_count" name="commision_verification_days_count" class="form-control" data-decimals="0" value="{{ setting('commision_verification_days_count') }}">
                        </div>

                        <div class="form-group">
                            <label for="affiliate_cookie_lifetime">@lang('Number of days affiliate cookie will expire.')</label>
                            <input type="number" id="affiliate_cookie_lifetime" name="affiliate_cookie_lifetime" class="form-control" data-decimals="0" value="{{ setting('affiliate_cookie_lifetime', 60) }}">
                        </div>

                        <div class="form-group w-100 mb-0">
                            <label class="mr-4">
                                @lang('Affiliate code type')
                            </label>
                            <select class="form-control ml-auto" name="affiliate_code_value_type" id="affiliate_code_value_type">
                                <option value="username" @selected(setting('affiliate_code_value_type') === 'username')>@lang('Username')</option>
                                <option value="string" @selected(setting('affiliate_code_value_type') === 'string')>@lang('Random 10 letter string')</option>
                                <option value="number" @selected(setting('affiliate_code_value_type') === 'number')>@lang('Random 10 digit number')</option>
                            </select>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Email settings')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Notify every admin on affiliate membership request of users')
                                </label>
                                <select class="form-control ml-auto" name="notify_admin_affiliate_membership_request" id="notify_admin_affiliate_membership_request">
                                    <option value="disable" @selected(setting('notify_admin_affiliate_membership_request') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('notify_admin_affiliate_membership_request') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Notify user on affiliate membership approved.')
                                </label>
                                <select class="form-control ml-auto" name="notify_user_affiliate_membership_approved" id="notify_user_affiliate_membership_approved">
                                    <option value="disable" @selected(setting('notify_user_affiliate_membership_approved') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('notify_user_affiliate_membership_approved') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Notify user on affiliate membership rejected.')
                                </label>
                                <select class="form-control ml-auto" name="notify_user_affiliate_membership_rejected" id="notify_user_affiliate_membership_rejected">
                                    <option value="disable" @selected(setting('notify_user_affiliate_membership_rejected') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('notify_user_affiliate_membership_rejected') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Notify user on incoming commissions.')
                                </label>
                                <select class="form-control ml-auto" name="notify_user_incoming_commissions" id="notify_user_incoming_commissions">
                                    <option value="disable" @selected(setting('notify_user_incoming_commissions') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('notify_user_incoming_commissions') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Notify user on commission approved by admin.')
                                </label>
                                <select class="form-control ml-auto" name="notify_user_approved_commissions" id="notify_user_approved_commissions">
                                    <option value="disable" @selected(setting('notify_user_approved_commissions') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('notify_user_approved_commissions') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-0">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Notify user on commission rejected by admin.')
                                </label>
                                <select class="form-control ml-auto" name="notify_user_rejected_commissions" id="notify_user_rejected_commissions">
                                    <option value="disable" @selected(setting('notify_user_rejected_commissions') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('notify_user_rejected_commissions') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                </div>
            </form>
        </div>
    </div>
@endsection
