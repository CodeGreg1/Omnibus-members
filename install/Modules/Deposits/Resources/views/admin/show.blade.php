@extends('deposits::layouts.master')

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection


@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.deposits.index') }}"> @lang('Deposits')</a>
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
                    @if ($method_image)
                        <img alt="image" src="{{ $method_image }}" class="profile-widget-picture">
                    @endif
                    <div class="profile-widget-items">
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label full-name">
                                {{ $method_name }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-widget-description pt-0 pb-0">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Date')</b>
                            <span>{{ $deposit->created_at->toUserTimezone()->toUserFormat() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Trx')</b>
                            <span>{{ $deposit->trx }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('User')</b>
                            <span>{{ $deposit->user->full_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Amount')</b>
                            <span>{{ currency_format($deposit->amount, $deposit->currency) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Charge')</b>
                            <span>{{ currency_format($deposit->charge, $deposit->currency) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Payable')</b>
                            <span>{{ currency_format($deposit->charge + $deposit->amount, $deposit->currency) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Status')</b>
                            @if ($deposit->rejected_at)
                                <span class="badge badge-danger">@lang('Rejected')</span>
                            @else
                                @if ($deposit->status)
                                    <span class="badge badge-primary">@lang('Completed')</span>
                                @else
                                    <span class="badge badge-secondary">@lang('Pending')</span>
                                @endif
                            @endif
                        </li>
                        @if ($deposit->rejected_at)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>@lang('Reject reason')</b>
                                @if ($deposit->reject_reason)
                                    <span>{{ $deposit->reject_reason }}</span>
                                @else
                                    <span class="text-muted">@lang('No reason')</span>
                                @endif
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="card-footer text-center p-2"></div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>@lang('Deposit details')</h4>
                </div>
                <div class="card-body">
                    @forelse ($deposit->details as $detail)
                        <div class="form-group">
                            <label class="d-block">{{ $detail->field_name }}</label>
                            @if ($detail->field_type === 'image_upload')
                                <img class="image-upload-preview" src="{{ $detail->value }}" alt="{{ $detail->field_name }}">
                            @else
                                <span>{{ $detail->value }}</span>
                            @endif
                        </div>
                    @empty
                        <div class="alert alert-info">
                            @lang('No details have been provided for this deposit.')
                        </div>
                    @endforelse
                </div>
                @if (!$deposit->status && is_null($deposit->rejected_at))
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary" type="button" id="btn-approve-manual-deposit" data-route="{{ route('admin.deposits.approve', $deposit) }}">
                            @lang('Approve')
                        </button>
                        <button class="btn btn-danger ml-3" type="button" id="btn-reject-manual-deposit" data-route="{{ route('admin.deposits.reject', $deposit) }}">
                            @lang('Reject')
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
