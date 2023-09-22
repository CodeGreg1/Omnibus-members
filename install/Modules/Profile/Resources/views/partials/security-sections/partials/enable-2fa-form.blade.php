<div class="col-12">
    <div class="row 2fa-content">
        <div class="col-md-6">
            <div class="form-group">
                <label for="country_code">@lang('Country Code')</label>
                <input type="text"
                       class="form-control"
                       id="country_code"
                       placeholder="63"
                       name="country_code"
                       value="{{ auth()->user()->authy_country_code }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone_number">@lang('Phone Number')</label>
                <input type="text"
                       class="form-control"
                       id="phone_number"
                       placeholder="@lang('Phone number without country code')"
                       name="phone_number"
                       value="{{ auth()->user()->authy_phone }}">
            </div>
        </div>
    </div> 
</div>