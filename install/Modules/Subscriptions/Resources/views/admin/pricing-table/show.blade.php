@extends('subscriptions::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/tom-select/dist/css/tom-select.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-daterangepicker/css/daterangepicker.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ url('plugins/bootstrap-daterangepicker/js/daterangepicker.js') }}"></script>
    <script src="{{ url('plugins/autosize/dist/autosize.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.subscriptions.pricing-tables.index') }}">@lang('Pricing tables')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <div class="d-flex align-items-center justify-content-between mt-4 mt-lg-0">
        @can('admin.subscriptions.pricing-tables.edit')
            <div class="btn-group ml-lg-2 mr-3">
                <a href="{{ route('admin.subscriptions.pricing-tables.edit', $pricingTable) }}" class="btn btn-outline-primary">
                    <i class="fas fa-pencil"></i>
                    @lang('Edit pricing table')
                </a>
            </div>
        @endcan

        @include('cashier::partials.live-mode-status')
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>@lang('Packages')</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">@lang('Name')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($packageList as $item)
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong>{{ $item->package->name }}</strong>
                                    @php
                                        $prices = [];
                                        foreach ($item->prices as $price) {
                                            $prices[] = $price->getPriceDescription();
                                        }
                                        $string = '';
                                        $last = array_pop($prices);
                                        if (count($prices)) {
                                            $string = implode(", ", $prices);
                                            $string .= ' and ' . $last;
                                        } else {
                                            $string = $last;
                                        }
                                    @endphp
                                    <div>
                                        {{ $string }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
