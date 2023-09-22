@extends('deposits::layouts.master')

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Deposit')</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>@lang('Deposit methods')</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive table-invoice">
                <table class="table table-hover table-custom-deposit">
                    <thead>
                        <tr>
                            <th>@lang('Method')</th>
                            <th>@lang('Min Amount')</th>
                            <th>@lang('Max Amount')</th>
                            <th>@lang('Charge')</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($automaticGateways as $automaticGateway)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img class="mr-3 rounded" width="55" src="{{ $automaticGateway->image }}" alt="{{ $automaticGateway->name }}">
                                        <div class="d-flex flex-column ml-4">
                                            <strong>
                                                {{ $automaticGateway->name }}
                                            </strong>
                                            <span class="text-muted">Automatic</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ currency_format($automaticGateway->min_limit, $automaticGateway->currency) }}</td>
                                <td>{{ currency_format($automaticGateway->max_limit, $automaticGateway->currency) }}</td>
                                <td>
                                    {{ currency_format(floatval($automaticGateway->fixed_charge), $automaticGateway->currency) }} + {{ floatval($automaticGateway->percent_charge) }}%
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('user.deposits.checkout.automatic', [
                                        'gateway' => $automaticGateway->gateway->key
                                    ]) }}" class="btn btn-primary">@lang('Deposit')</a>
                                </td>
                            </tr>
                        @endforeach
                        @foreach ($manualGateways as $manualGateway)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img class="mr-3 rounded" width="55" src="{{ $manualGateway->getPreviewImage() }}" alt="{{ $manualGateway->name }}">
                                        <div class="d-flex flex-column ml-4">
                                            <strong>
                                                {{ $manualGateway->name }}
                                            </strong>
                                            <span class="text-muted">Manual</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ currency_format($manualGateway->min_limit, $manualGateway->currency) }}</td>
                                <td>{{ currency_format($manualGateway->max_limit, $manualGateway->currency) }}</td>
                                <td>{{ currency_format(floatval($manualGateway->fixed_charge), $manualGateway->currency) }} + {{ floatval($manualGateway->percent_charge) }}%</td>
                                <td class="text-right">
                                    <a href="{{ route('user.deposits.checkout.manual', $manualGateway) }}" class="btn btn-primary">@lang('Deposit')</a>
                                </td>
                            </tr>
                        @endforeach
                        @if (!$automaticGateways->count() && !$manualGateways->count())
                            <tr>
                                <td colspan="5">
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
