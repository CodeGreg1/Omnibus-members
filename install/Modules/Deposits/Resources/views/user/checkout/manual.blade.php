@extends('carts::layouts.checkout')

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="{{ url('plugins/autosize/dist/autosize.min.js') }}"></script>
    <script src="{{ url('plugins/dropzone/js/dropzone.min.js') }}"></script>
@endsection

@section('content')
    <div class="checkout-section manual-deposit-checkout" id="deposit-checkout">
        <input type="hidden" name="currency" value="{{ $gateway->currency }}" />
        <input type="hidden" id="method-currency" value="{{ $gateway->currency }}" />
        <input type="hidden" id="method-min" value="{{ $gateway->min_limit }}" />
        <input type="hidden" id="method-max" value="{{ $gateway->max_limit }}" />
        <input type="hidden" id="method-fixed-charge" value="{{ $gateway->fixed_charge ?? 0 }}" />
        <input type="hidden" id="method-percent-charge" value="{{ $gateway->percent_charge ?? 0 }}" />
        <div class="checkout-column">
            <div class="checkout-overview">
                <div class="d-flex align-items-center">
                    <a href="{{ route('user.deposits.index') }}" class="checkout-header-brand">
                        <i class="fas fa-arrow-left checkout-header-brand-icon"></i>
                        <span class="checkout-header-brand-title">
                            @lang('Back to Deposit')
                        </span>
                    </a>
                </div>

                <div class="form-group mt-3">
                    <label class="d-block" for="amount">@lang('Amount')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-lg">{{ $gateway->currency }}</div>
                        </div>
                        <input type="text" name="amount" data-decimals="2" class="form-control form-control-lg" placeholder="0.00">
                    </div>
                </div>

                <label for="">
                    @lang('Charges')
                    <em class="text-white">({{ $gateway->currency }} {{currency_format($gateway->fixed_charge ?? 0, $gateway->currency)}} + {{ $gateway->percent_charge ?? 0 }}%)</em>
                </label>
                <div class="Checkout-OrderDetails-footer Items-no-margin-left">
                    <div class="Checkout-OrderDetailsFooter-subtotal flex-container justify-content-between align-items-end mb-0">
                        <div class="d-flex flex-column">
                            <label class="text-sm mb-0">@lang('Fixed')</label>
                            <div class="d-flex">
                                <span class="m-0 text-white fw-bolder">{{ $gateway->currency }}</span>
                                <span class="m-0 text-white fw-bolder ml-1">{{ currency_format($gateway->fixed_charge ?? 0, $gateway->currency) }}</span>
                            </div>
                        </div>
                        <span class="m-0 text-white fw-bolder">
                            <span id="checkout-fixed-charge">{{ currency_format($gateway->fixed_charge ?? 0, $gateway->currency) }}</span>
                        </span>
                    </div>

                    <div class="Checkout-OrderDetailsFooter-subtotal flex-container justify-content-between align-items-end">
                        <div class="d-flex flex-column">
                            <label class="text-sm mb-0">@lang('Percent')</label>
                            <div class="d-flex">
                                <span class="m-0 text-white fw-bolder">{{ $gateway->percent_charge ?? 0 }}%</span>
                            </div>
                        </div>
                        <span class="m-0 text-white fw-bolder">
                            <span id="checkout-percent-charge">{{ currency_format(0, $gateway->currency) }}</span>
                        </span>
                    </div>

                    <div class="Checkout-OrderDetails-total flex-container justify-content-between">
                        <span>
                            <span class="m-0 text-white fw-bolder">@lang('Total due today')</span>
                        </span>
                        <span class="m-0 text-white fw-bolder">
                            <span class="checkout-total text-lg">{{ currency_format(0, $gateway->currency) }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="checkout-payment">
                <form class="d-flex flex-column mt-3 mt-lg-0" id="checkout-form">
                    <span class="column-title text-lg font-weight-600 checkout-gateway-label">Gateway</span>
                    <div class="d-flex align-items-right checkout-gateway-logo ml-25">
                        <div class="logo-container">
                            <img src="{{ $gateway->getPreviewImage() }}" alt="{{ $gateway->name }}">
                        </div>
                    </div>
                    <header class="column-header mb-2">
                        <span class="column-title text-lg font-weight-600">@lang('Range limit')</span>
                    </header>
                    <div class="border rounded mb-3 text-lg">
                        <div class="d-flex align-items-center px-3 py-2">
                            <i class="fas fa-arrow-up mr-3 text-success"></i>
                            <strong>@lang('Max')</strong>
                            <span class="mx-2">&#8226;</span>
                            <span class="mr-2 range-max">{{ currency_format($gateway->max_limit ?? 0, $gateway->currency) }}</span>
                        </div>
                        <div class="d-flex align-items-center px-3 py-2 border-top">
                            <i class="fas fa-arrow-down mr-3 text-danger"></i>
                            <strong>@lang('Min')</strong>
                            <span class="mx-2">&#8226;</span>
                            <span class="mr-2 range-min">{{ currency_format($gateway->min_limit ?? 0, $gateway->currency) }}</span>
                        </div>
                    </div>

                    @if ($gateway->user_data && count($gateway->user_data))
                        <div class="mt-3">
                            @if ($gateway->instructions)
                                <header class="column-header mb-2">
                                    <span class="column-title text-lg font-weight-600">@lang('Instruction')</span>
                                </header>
                                {!! $gateway->instructions !!}
                            @else
                                <header class="column-header mb-2">
                                    <span class="column-title text-lg font-weight-600">@lang('Details')</span>
                                </header>
                            @endif

                            @foreach ($gateway->user_data as $user_data)
                                @if ($user_data->field_type === 'textarea')
                                    <div class="form-group">
                                        <label for="{{ $user_data->field_name }}">
                                            {{ $user_data->field_name }}
                                            @if ($user_data->required)
                                                <span class="text-muted">(@lang('Required'))</span>
                                            @endif
                                        </label>
                                        <textarea name="{{ $user_data->field_name }}" class="form-control" placeholder="{{ $user_data->field_name }}"></textarea>
                                    </div>
                                @elseif ($user_data->field_type === 'image_upload')
                                    <div class="form-group">
                                        <label for="{{ $user_data->field_name }}">
                                            {{ $user_data->field_name }}
                                            @if ($user_data->required)
                                                <span class="text-muted">(@lang('Required'))</span>
                                            @endif
                                        </label>
                                        <div class="dropzone dropzone-multiple checkout-image" data-toggle="dropzone1" data-dropzone-url="http://"
                                        data-field-name="{{ $user_data->field_name }}"
                                        data-dropzone-multiple>
                                            <div class="fallback">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="dropzone-1" name="{{ $user_data->field_name }}" multiple>
                                                    <label class="custom-file-label" for="customFileUpload">{{__('Choose file')}}</label>
                                                </div>
                                            </div>
                                            <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                                                <li class="list-group-item px-0">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <div class="avatar">
                                                                <img class="rounded" src="{{ url('upload/media/default/dr-default.png') }}" alt="Image placeholder" data-dz-thumbnail>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <h6 class="text-sm mb-1" data-dz-name>...</h6>
                                                            <p class="small text-muted mb-0" data-dz-size></p>
                                                        </div>
                                                        <div class="col-auto">
                                                            <a href="#" class="dropdown-item" data-dz-remove>
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="{{ $user_data->field_name }}">
                                            {{ $user_data->field_name }}
                                            @if ($user_data->required)
                                                <span class="text-muted">(@lang('Required'))</span>
                                            @endif
                                        </label>
                                        <input name="{{ $user_data->field_name }}" class="form-control" placeholder="{{ $user_data->field_name }}">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </form>

                <button type="button" class="btn btn-primary btn-block btn-lg mt-4 btn-gateway-checkout-uppercase-style" id="btn-submit-manual-deposit-checkout" data-route="{{ route('user.deposits.checkout.manual.process', $gateway) }}">
                    @lang('Continue with :name', [
                        'name' => $gateway->name
                    ])
                </button>
            </div>
        </div>
    </div>
@endsection
