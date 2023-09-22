<div class="billing-addresses {{$checkout->collect_shipping_address ? 'd-none' : ''}}">
    <p class="text-dark mb-1">
        @lang('Billing address')
    </p>
    @if (count($addresses))
        @foreach ($addresses as $key => $address)
            <div class="mb-2 list-select-control">
                <input type="radio" id="billing_address_id_{{ $address->id }}" name="billing_address_id" class="list-select-control-input" value="{{ $address->id }}" @checked(!$key)>
                <label class="list-select-control-label" for="billing_address_id_{{ $address->id }}">
                    <strong class="text-dark">
                        {{ $address->name }}
                    </strong>
                    <p class="mb-0 lh-base">
                        {{ $address->address_1 }},
                        {{ $address->address_2 ? $address->address_2 . ', ' : '' }}
                        {{ $address->city ? $address->city . ', ' : '' }}
                        {{ $address->state ? $address->state . ', ' : '' }}
                        {{ $address->country->name }},
                        {{ $address->zip_code }}
                    </p>
                </label>
            </div>
        @endforeach
        <div class="mb-2 list-select-control">
            <input type="radio" id="billing_address_id_0" name="billing_address_id" class="list-select-control-input" value="0" @checked(!count($addresses))>
            <label class="list-select-control-label" for="billing_address_id_0">
                <span class="text-dark">
                    @lang('New billing address')
                </span>
                @include('carts::checkout.partials.form.billing-address')
            </label>
        </div>

    @else
        @include('carts::checkout.partials.form.billing-address')
    @endif
</div>
