@extends('subscriptions::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/tom-select/dist/css/tom-select.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-daterangepicker/css/daterangepicker.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ url('plugins/bootstrap-daterangepicker/js/daterangepicker.js') }}"></script>
    <script src="{{ url('plugins/autosize/dist/autosize.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
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

@section('scripts')
<script>
    let items = @json($pricingTable->items->pluck('package_price_id'));
    items = items.map(item => String (item));
    app.pricingTable.config.plans.items = items;
    app.pricingTable.config.data = {!!
        $pricingTable->items->map(function($item) {
            return (object) [
                'id' => $item->package_price_id,
                'name' => $item->price->amount_display,
                'package' => $item->price->package->name,
                'class' => $item->price->package_id
            ];
        })
    !!};
</script>
@endsection

@section('module-actions')
    <div class="mt-4 mt-lg-0">
        @include('cashier::partials.live-mode-status')
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <form id="updatePricingTableForm">
                    <div class="form-group">
                        <label for="name">@lang('Name')</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ $pricingTable->name }}">
                    </div>
                    <div class="form-group">
                        <label for="name">
                            @lang('Description')
                            <span class="text-muted">(@lang('Optional'))</span>
                        </label>
                        <input type="text" class="form-control" name="description" id="description" value="{{ $pricingTable->description }}">
                    </div>

                    <h6>@lang('Packages')</h6>
                    <div class="alert alert-info">
                        <p>@lang('When changes to ordering are made, Click "Save Changes" to save.')</p>
                    </div>
                    <table class="table table-sm" id="pricing-table-packages">
                        <tbody>
                            @foreach ($pricingTable->items as $item)
                                <tr role="row" class="pricing-table-item" data-id="{{ $item->package_price_id }}">
                                    <td class="pricing-table-item-drag-handle">
                                        <span>
                                            <i class="fas fa-arrows-alt drag-handle"></i>
                                        </span>
                                    </td>
                                    <td class="pricing-table-item-details">
                                        <div class="accordion">
                                            <div class="accordion-header collapsed" role="button" data-toggle="collapse" data-target="#panel-body-{{ $item->package_price_id }}" aria-expanded="false">
                                                <div class="d-flex flex-column">
                                                    <strong>{{ $item->price->package->name }}</strong>
                                                    <span>
                                                        @php
                                                            $description = $item->price->user_amount_display . ' every ';
                                                            if ($item->price->isRecurring()) {
                                                                $description = $item->price->user_amount_display . ' every ';
                                                                if ($item->price->term->interval_count > 1) {
                                                                    $description .= $item->price->term->interval_count . ' ' . $item->price->term->interval . 's';
                                                                } else {
                                                                    $description .= $item->price->term->interval;
                                                                }
                                                            } else {
                                                                $description = $item->price->user_amount_display;
                                                            }

                                                        @endphp
                                                        {{ $description }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="accordion-body collapse px-0" id="panel-body-{{ $item->package_price_id }}" data-parent="#pricing-table-packages" style="">
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input allow_promo_code" id="allow_promo_code_{{ $item->package_price_id }}" @checked($item->allow_promo_code)>
                                                    <label class="custom-control-label" for="allow_promo_code_{{ $item->package_price_id }}">
                                                        <strong>@lang('Allow promotion codes')</strong>
                                                    </label>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label>@lang('Button label') <span class="text-muted text-sm">(@lang('Optional'))</span></label>
                                                    <input type="text" class="form-control button_label" value="{{ $item->button_label }}">
                                                    <small class="form-text text-muted form-help">@lang('Custom button label for frontend only').</small>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label>@lang('Button link') <span class="text-muted text-sm">(@lang('Optional'))</span></label>
                                                    <input type="text" class="form-control button_link" value="{{ $item->button_link }}">
                                                    <small class="form-text text-muted form-help">@lang('Custom button link for frontend only').</small>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label>@lang('Confirm page message') <span class="text-muted text-sm">(@lang('Optional'))</span></label>
                                                    <textarea class="form-control confirm_page_message" id="confirm_page_message_{{ $item->package_price_id }}">{{ $item->confirm_page_message }}</textarea>
                                                    <small class="form-text text-muted">
                                                        @lang("Please note that custom messages aren't translated based on your customer's language.")
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pricing-table-item-remove-handle">
                                        <a href="javascript:void(0)" role="button" class="btn-remove-pricing-table-item">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="form-group mb-0">
                        <select id="pricing-table-search-packages" placeholder="@lang('Pick a package')" multiple></select>
                    </div>

                    <div class="mt-3">
                        <div class="form-group mb-0">
                            <label>@lang('Featured package')</label>
                            <select class="custom-select" id="featured_package" name="featured_package">
                                <option selected="" value="">@lang('Dont set featured package')</option>
                                @foreach ($packages as $package)
                                    <option @selected($package->featured) value="{{ $package->id}}">
                                            {{ $package->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-footer bg-whitesmoke">
                <button type="submit" class="btn btn-primary btn-lg float-right" id="btn-update-pricing-table" data-route="{{ route('admin.subscriptions.pricing-tables.update', $pricingTable) }}">@lang('Save changes')</button>
            </div>
        </div>
    </div>
</div>
@endsection
