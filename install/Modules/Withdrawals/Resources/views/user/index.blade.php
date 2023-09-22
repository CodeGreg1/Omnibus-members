@extends('deposits::layouts.master')

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Withdraw')</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    <div class="card">
        <div class="card-header">
            <h4>@lang('Withdrawal methods')</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive table-invoice">
                <table class="table table-hover table-custom-deposit">
                    <thead>
                        <tr>
                            <th>@lang('Method')</th>
                            <th>@lang('Currency')</th>
                            <th>@lang('Min Amount')</th>
                            <th>@lang('Max Amount')</th>
                            <th>@lang('Charge')</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($methods as $method)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img class="mr-3 rounded" width="55" src="{{ $method->getPreviewImage() }}" alt="{{ $method->name }}">
                                        <strong class="ml-2">
                                            {{ $method->name }}
                                        </strong>
                                    </div>
                                </td>
                                <td>{{ $method->currency }}</td>
                                <td>{{ currency_format($method->min_limit, $method->currency) }}</td>
                                <td>{{ currency_format($method->max_limit, $method->currency) }}</td>
                                <td>{{ currency_format(floatval($method->fixed_charge), $method->currency) }} + {{ floatval($method->percent_charge) }}%</td>
                                <td class="text-right">
                                <a href="{{ route('user.withdrawals.checkout.create', $method) }}" class="btn btn-primary {{ $hasFunds ? '' : 'disabled' }}" @disabled(!$hasFunds)>@lang('Withdraw')</a>
                                </td>
                            </tr>
                        @endforeach

                        @if (!$methods->count())
                            <tr>
                                <td colspan="6">
                                    <div class="text-muted py-4 text-center">@lang('No methods')</div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
