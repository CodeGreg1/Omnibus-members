@extends('orders::layouts.master')

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> {{ trans('Dashboard') }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('user.orders.index') }}"> {{ trans('Orders') }}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! $pageTitle !!}</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <div class="btn-group ml-2">
        @can('user.orders.cancel', $order)
            <a href="#" role="button" data-route="{{ route('user.orders.cancel', $order) }}" class="btn btn-danger btn-cancel-order">
                @lang('Cancel order')
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h6>@lang('Order details')</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td class="w-50">
                                    <strong>@lang('Customer:')</strong>
                                </td>
                                <td class="w-50">
                                    {{ $order->owner->full_name }}
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50">
                                    <strong>@lang('Email:')</strong>
                                </td>
                                <td class="w-50">
                                    {{ $order->owner->email }}
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50">
                                    <strong>@lang('Shipping address:')</strong>
                                </td>
                                <td class="w-50">
                                    <address>
                                        {{ $order->shippingAddress->name }}<br>
                                        {{ $order->shippingAddress->address_1 }}<br>
                                        @if ($order->shippingAddress->address_2)
                                            {{ $order->shippingAddress->address_2 }}<br>
                                        @endif
                                        {{ $order->shippingAddress->city }} {{ $order->shippingAddress->state }}<br>
                                        {{ $order->shippingAddress->country->name }} {{ $order->shippingAddress->zip_code }}<br>
                                    </address>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6 col-12">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td class="w-50">
                                    <strong>@lang('Date:')</strong>
                                </td>
                                <td class="w-50">
                                    {{ $order->created_at }}
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50">
                                    <strong>@lang('Status:')</strong>
                                </td>
                                <td class="w-50">
                                    {{ $order->status->getLabel() }}
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50">
                                    <strong>@lang('Payment:')</strong>
                                </td>
                                <td class="w-50">
                                    @if ($order->paid)
                                        <strong class="text-success">@lang('Paid')</strong>
                                    @else
                                        <strong class="text-danger">@lang('Unpaid')</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50">
                                    <strong>@lang('Payment method:')</strong>
                                </td>
                                <td class="w-50">
                                    {{ $paymentProvider->name }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h6>@lang('Order items')</h6>
                </div>
                <div class="car-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="pr-0" style="width: 60px;">
                                    @lang('Item')
                                </th>
                                <th>

                                </th>
                                <th>
                                    @lang('Unit price')
                                </th>
                                <th>
                                    @lang('Qty')
                                </th>
                                <th>
                                    @lang('Total')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                            @php
                                $image = '/themes/stisla/assets/img/products/product-5-50.png';
                                $attributes = [];
                                if ($item->attributes) {
                                    $attributes = (array) json_decode($item->attributes);
                                    $image = $attributes['image'] ?? $image;
                                    unset($attributes['image']);
                                }
                            @endphp
                                <tr>
                                    <td class="pr-0">
                                        <img class="rounded" width="55" src="{{ $image }}" alt="product">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <a href="{{ $item->orderable->purchasable_path }}" class="fw-bolder">
                                                {{ $item->title }}
                                            </a>
                                            @if (count($attributes))
                                                 <div class="mt-2 text-sm">
                                                    <div class="space-initial">
                                                        @foreach ($attributes as $key => $value)
                                                            <span class="d-inline-block mr-3 mb-1">
                                                                <span class="text-muted mr-1">
                                                                    {{ $key }}
                                                                </span>
                                                                <span>
                                                                    {{ $value }}
                                                                </span>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                 </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{
                                            $item->orderable->unit_price
                                        }}
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        <strong>
                                            {{
                                                $item->getUnitPrice()
                                            }}
                                        </strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h6>@lang('Order summary')</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td>
                                    @lang('Subtotal')
                                </td>
                                <td>
                                    {{ $order->getSubtotal() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @lang('Shipping')
                                </td>
                                <td>
                                    {{ $order->getShippingAmount() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @lang('Tax')
                                </td>
                                <td>
                                    {{ $order->getTotalTax() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @lang('Discount')
                                </td>
                                <td>
                                    {{ $order->getTotalDiscount() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <Strong>@lang('Total')</Strong>
                                </td>
                                <td>
                                    <strong>
                                        {{ $order->getTotal() }}
                                    </strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
