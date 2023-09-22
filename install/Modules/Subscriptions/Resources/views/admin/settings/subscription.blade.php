@extends('settings::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="{{ url('plugins/dropzone/js/dropzone.min.js') }}"></script>
@endsection

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
            <form id="admin-settings-edit-form" method="post">
                @method('patch')
                <input type="hidden" name="redirect" value="/admin/settings/subscriptions">

                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Subscription settings')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Allow subscriptions')
                                </label>
                                <select class="form-control ml-auto" name="allow_subscriptions" id="allow_subscriptions">
                                    <option value="disable" @selected(setting('allow_subscriptions') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_subscriptions') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-inline w-100 mb-0">
                            <div class="form-group w-100">
                                <label class="mr-4">@lang('Number of days for subscription expiration date extension.')</label>
                                <input type="number" class="form-control ml-auto text-right" name="subscription_expiration_extension" id="subscription_expiration_extension" value="{{ setting('subscription_expiration_extension', 0) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">

                    <div class="card-header">
                        <h4>@lang('Subscription email notifications')</h4>
                    </div>
                    <div class="card-body">
                        <div class="custom-control mb-3 pl-0">
                            <label>
                                @lang('Send an email to every admin on every subscription.')
                            </label>
                        </div>

                        <div class="border border-top-0 border-left-0 border-right-0 mb-4">
                            <div class="form-inline w-100 mb-4">
                                <div class="form-group w-100">
                                    <label class="mr-4">
                                        @lang('Subscription created')
                                    </label>
                                    <select class="form-control ml-auto" name="admin_subscription_success_email_status" id="admin_subscription_success_email_status">
                                        <option value="disable" @selected(setting('admin_subscription_success_email_status') === 'disable')>@lang('Disable')</option>
                                        <option value="enable" @selected(setting('admin_subscription_success_email_status') === 'enable')>@lang('Enable')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-inline w-100 mb-4">
                                <div class="form-group w-100">
                                    <label class="mr-4">
                                        @lang('Subscription cancelled')
                                    </label>
                                    <select class="form-control ml-auto" name="admin_subscription_cancelled_email_status" id="admin_subscription_cancelled_email_status">
                                        <option value="disable" @selected(setting('admin_subscription_cancelled_email_status') === 'disable')>@lang('Disable')</option>
                                        <option value="enable" @selected(setting('admin_subscription_cancelled_email_status') === 'enable')>@lang('Enable')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-inline w-100 mb-4">
                                <div class="form-group w-100">
                                    <label class="mr-4">
                                        @lang('Subscription expired')
                                    </label>
                                    <select class="form-control ml-auto" name="admin_subscription_expired_email_status" id="admin_subscription_expired_email_status">
                                        <option value="disable" @selected(setting('admin_subscription_expired_email_status') === 'disable')>@lang('Disable')</option>
                                        <option value="enable" @selected(setting('admin_subscription_expired_email_status') === 'enable')>@lang('Enable')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-inline w-100 mb-4">
                                <div class="form-group w-100">
                                    <label class="mr-4">
                                        @lang('Subscription package changed')
                                    </label>
                                    <select class="form-control ml-auto" name="admin_subscription_package_change_status" id="admin_subscription_package_change_status">
                                        <option value="disable" @selected(setting('admin_subscription_package_change_status') === 'disable')>@lang('Disable')</option>
                                        <option value="enable" @selected(setting('admin_subscription_package_change_status') === 'enable')>@lang('Enable')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-inline w-100 mb-4">
                                <div class="form-group w-100">
                                    <label class="mr-4">
                                        @lang('Subscription payment completed')
                                    </label>
                                    <select class="form-control ml-auto" name="admin_subscription_payment_completed_email_status" id="admin_subscription_payment_completed_email_status">
                                        <option value="disable" @selected(setting('admin_subscription_payment_completed_email_status') === 'disable')>@lang('Disable')</option>
                                        <option value="enable" @selected(setting('admin_subscription_payment_completed_email_status') === 'enable')>@lang('Enable')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-inline w-100 mb-4">
                                <div class="form-group w-100">
                                    <label class="mr-4">
                                        @lang('Subscription payment failed')
                                    </label>
                                    <select class="form-control ml-auto" name="admin_subscription_payment_failed_email_status" id="admin_subscription_payment_failed_email_status">
                                        <option value="disable" @selected(setting('admin_subscription_payment_failed_email_status') === 'disable')>@lang('Disable')</option>
                                        <option value="enable" @selected(setting('admin_subscription_payment_failed_email_status') === 'enable')>@lang('Enable')</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send an email after successful subscription.')
                                </label>
                                <select class="form-control ml-auto" name="subscription_success_email_status" id="subscription_success_email_status">
                                    <option value="disable" @selected(setting('subscription_success_email_status') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('subscription_success_email_status') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send an email after the subscription is cancelled.')
                                </label>
                                <select class="form-control ml-auto" name="subscription_cancelled_email_status" id="subscription_cancelled_email_status">
                                    <option value="disable" @selected(setting('subscription_cancelled_email_status') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('subscription_cancelled_email_status') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">@lang('Send an email after the subscription expired.')</label>
                                <select class="form-control ml-auto" name="subscription_expired_email_status" id="subscription_expired_email_status">
                                    <option value="disable" @selected(setting('subscription_expired_email_status') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('subscription_expired_email_status') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send an email if subscription payment completed.')
                                </label>
                                <select class="form-control ml-auto" name="subscription_payment_completed_email_status" id="subscription_payment_completed_email_status">
                                    <option value="disable" @selected(setting('subscription_payment_completed_email_status') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('subscription_payment_completed_email_status') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send an email if subscription payment failed.')
                                </label>
                                <select class="form-control ml-auto" name="subscription_payment_failed_email_status" id="subscription_payment_failed_email_status">
                                    <option value="disable" @selected(setting('subscription_payment_failed_email_status') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('subscription_payment_failed_email_status') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-info alert-has-icon">
                            <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                            <div class="alert-body">
                                <div class="alert-title">@lang('Note')</div>
                                @lang('Settings below needs cron job in order to work.')
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">@lang('Send an email notification before billing payment due.')</label>
                                <select class="form-control ml-auto" name="subscription_payment_incoming_email_status" id="subscription_payment_incoming_email_status">
                                    <option value="disable" @selected(setting('subscription_payment_incoming_email_status') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('subscription_payment_incoming_email_status') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-0">
                            <div class="form-group w-100">
                                <label class="mr-4">@lang('Set number of days before email notification for billing payment due will sent.')</label>
                                <input type="number" class="form-control ml-auto text-right" name="subscription_payment_incoming_email_days" id="subscription_payment_incoming_email_days" value="{{ setting('subscription_payment_incoming_email_days', 1) }}">
                            </div>
                        </div>

                    </div>
                </div>

                <div>
                    <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                    <a href="{{ route('admin.settings.subscriptions') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                </div>
            </form>
        </div>
    </div>
@endsection
