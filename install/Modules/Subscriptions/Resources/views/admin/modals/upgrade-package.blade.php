<div class="modal fade" tabindex="-1" role="dialog" id="upgarde-package-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Change package')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-feature-form" data-route="">
                    <div class="row g-3">
                        <div class="col-sm-9">
                            <label class="form-label" for="choosePlan">@lang('Choose package')</label>
                            <select id="choosePlan" name="choosePlan" class="form-control form-control-sm" aria-label="Choose package">
                                <option value="" selected>@lang('Choose package')</option>
                                @foreach ($pricingTable as $index => $table)
                                    @foreach ($table->prices as $item)
                                        <option value="{{ route('user.subscriptions.change-package.create', [
                                            'subscription' => $subscription->id,
                                            'price' => $item->price->id
                                        ]) }}">
                                            {{ $item->price->package->name }} - {{ $item->price->user_amount_display }}
                                            @if ($item->price->type === 'recurring')
                                                / {{ $item->price->term->description() }}
                                            @else
                                                - forever
                                            @endif
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary disabled" id="btn-subscription-upgrade-package" disabled="disabled">@lang('Continue')</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-body border-top">
                <strong>
                    @lang('User current package is :package', [
                        'package' => $subscription->item->price->package->name
                    ])
                </strong>
                <div class="row mt-3">
                    <div class="col-lg-6 col-12">
                        <div class="d-flex">
                            <sup class="h5 pricing-currency mt-3 mb-0 me-1 text-primary">{{ $subscription->currency }}</sup>
                            <h1 class="display-5 mb-0 text-primary">{{ $subscription->getTotal() }}</h1>
                            @if ($subscription->isRecurring())
                                <sub class="pricing-duration mt-auto mb-3"><span>/</span>@php
                                    if ($subscription->item->price->term->interval_count > 1) {
                                        echo $subscription->item->price->term->interval_count . ' ' . $subscription->item->price->term->interval . 's';
                                    } else {
                                        echo $subscription->item->price->term->interval;
                                    }
                                @endphp</sub>
                            @else
                                <sub class="pricing-duration mt-auto mb-3">forever</sub>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-danger btn-cancel-subscription mt-2" href="javascript:void(0)" role="button" data-id="{{ $subscription->id }}">
                                @lang('Cancel subscription')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
