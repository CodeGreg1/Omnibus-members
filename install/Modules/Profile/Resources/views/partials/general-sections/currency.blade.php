<div class="card card-form" id="currency">
    <div class="card-header">
        <h4><i class="fas fa-dollar-sign"></i> @lang('Currency')</h4>
    </div>

    <form id="profile-currency-form" method="post">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group mb-0">
                        <label>@lang('Currency')</label>
                        <select name="currency" 
                            class="form-control select2">
                            <option value="">@lang('Select currency')</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->code }}" 
                                    {{ $user 
                                        ? $user->currency == $currency->code
                                            ? 'selected' 
                                            : '' 
                                        : '' }}>{{ $currency->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-whitesmoke">
            <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
        </div> 
    </form>
                     
</div>