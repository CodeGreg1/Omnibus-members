<div class="card card-form" id="address">
    <div class="card-header">
        <h4><i class="fas fa-address-card"></i> @lang('Address')</h4>
    </div>

    <form id="profile-address-form" method="post">
        <input value="{{ $address->id ?? '' }}" 
            name="address_id" 
            class="form-control" 
            type="hidden">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('Country')</label>
                        <select name="country" 
                            class="form-control select2">
                            <option value="">@lang('Select country')</option>
                            @foreach($countries as $country)
                                @if(isset($address->country_id))
                                    <option value="{{ $country->id }}" 
                                        {{ $address->country_id == $country->id 
                                              ? 'selected' 
                                              : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @else
                                    <option value="{{ $country->id }}"
                                        {{ auth()->user()->country_id == $country->id 
                                              ? 'selected' 
                                              : '' }}>{{ $country->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="address">@lang('Address')</label>
                        <input value="{{ $address->address_1 ?? '' }}" 
                            name="address" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="apartment">@lang('Apartment, suite, etc.')</label>
                        <input value="{{ $address->address_2 ?? '' }}"
                             name="apartment" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="city">@lang('City')</label>
                        <input value="{{ $address->city ?? '' }}"
                            name="city" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="state">@lang('State/Province/Region')</label>
                        <input value="{{ $address->state ?? '' }}"
                            name="state" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group mb-0">
                        <label for="zip_code">@lang('ZIP code')</label>
                        <input value="{{ $address->zip_code ?? '' }}"
                            name="zip_code" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>
            </div>
        </div>
         
        <div class="card-footer bg-whitesmoke">
            <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
            <!-- <button class="btn btn-secondary btn-lg float-right"  type="button"><i class="fa fa-remove"></i> Cancel</button> -->
        </div>
    </form>
          
</div>