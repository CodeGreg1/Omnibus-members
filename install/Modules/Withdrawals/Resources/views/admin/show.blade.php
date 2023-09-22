@extends('withdrawals::layouts.master')

@section('module-scripts')
    <script src="{{ url('plugins/dropzone/js/dropzone.min.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection


@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.withdrawals.index') }}"> @lang('Withdraw requests')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! $pageTitle !!}</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="row admin-view-user">
        <div class="col-12 col-sm-12 col-lg-4">
            <div class="card profile-widget admin-view-user">
                <div class="profile-widget-header">
                    <img alt="image" src="{{ $withdraw->method->getPreviewImage() }}" class="profile-widget-picture">
                    <div class="profile-widget-items">
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label full-name">
                                {{ $withdraw->method->name }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-widget-description pt-0 pb-0">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Date')</b>
                            <span>{{ $withdraw->created_at->toUserTimezone()->toUserFormat() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Trx')</b>
                            <span>{{ $withdraw->trx }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('User')</b>
                            <span>{{ $withdraw->user->full_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Amount')</b>
                            <span>{{ currency_format($withdraw->amount, $withdraw->currency) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Charge')</b>
                            <span>{{ currency_format($withdraw->charge, $withdraw->currency) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Receivable')</b>
                            <span>{{ currency_format($withdraw->amount - $withdraw->charge, $withdraw->currency) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Status')</b>
                            @if ($withdraw->rejected_at)
                                <span class="badge badge-danger">@lang('Rejected')</span>
                            @else
                                @if ($withdraw->status)
                                    <span class="badge badge-primary">@lang('Completed')</span>
                                @else
                                    <span class="badge badge-secondary">@lang('Pending')</span>
                                @endif
                            @endif
                        </li>
                        @if ($withdraw->rejected_at)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>@lang('Reject reason')</b>
                                @if ($withdraw->reject_reason)
                                    <span>{{ $withdraw->reject_reason }}</span>
                                @else
                                    <span class="text-muted">@lang('No reason')</span>
                                @endif
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="card-footer text-center p-2">
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-lg-8">
            @if (!$hasFunds && !$withdraw->status && is_null($withdraw->rejected_at))
                <div class="alert alert-danger alert-has-icon">
                    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                    <div class="alert-body">
                    <div class="alert-title"></div>
                        @lang('The user has insufficient balance. Kindly reject this request.')
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>@lang('Withdrawal details')</h4>
                </div>
                <div class="card-body">
                    @php
                        $lastKey = key(array_slice($details, -1, 1, true));
                    @endphp
                    @forelse ($details as $key => $detail)
                        @isset($detail->value)
                            <div class="form-group {{ $lastKey === $key ? 'mb-0' : '' }}">
                                <label class="d-block">{{ $detail->field_name }}</label>
                                @if ($detail->field_type === 'image_upload')
                                    <img class="w-100" src="{{ $detail->value }}" alt="{{ $detail->field_name }}" style="max-width: 300px;">
                                @else
                                    <span>{{ $detail->value }}</span>
                                @endif
                            </div>
                        @endisset
                    @empty
                        <div class="alert alert-info">
                            @lang('No details has been provided for this withdrawal.')
                        </div>
                    @endforelse
                </div>
                @if (!$withdraw->status && is_null($withdraw->rejected_at))
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary {{ $hasFunds ? '' : 'disabled' }}" type="button" data-toggle="modal" data-target="#withdrawal-confirm-approve-modal" @disabled(!$hasFunds)>
                            @lang('Approve')
                        </button>
                        <button class="btn btn-danger ml-3" type="button" id="btn-reject-withdraw-request" data-route="{{ route('admin.withdrawals.reject', $withdraw) }}">
                            @lang('Reject')
                        </button>
                    </div>
                @endif
            </div>
            @if ($withdraw->additional_image->count() || $withdraw->note)
                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Withdrawal approve information')</h4>
                    </div>
                    <div class="card-body">
                        @if ($withdraw->note)
                            <div class="form-group {{ $withdraw->additional_image->count() ? '' : 'mb-0' }}">
                                <label class="d-block">@lang('Note')</label>
                                <span>{{ $withdraw->note }}</span>
                            </div>
                        @endif
                        @if ($withdraw->additional_image->count())
                            <div class="form-group mb-0">
                                <label class="d-block">@lang('Image')</label>
                                <img class="w-100" src="{{ $withdraw->additional_image[0]->url }}" alt="Image" style="max-width: 300px;">
                            </div>
                        @endif
                </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('modals')
    @include('withdrawals::admin.modals.confirm-approve')
@endsection
