<div class="card card-form" id="details">
    <div class="card-header">
        <h4>{{ $pageTitle }}</h4>
    </div>

    <form id="user-details-form" method="post" data-action="{{ route('admin.users.update', $user->id) }}">
        @method('patch')
        @csrf
        <input value="{{ $company ? $company->id : '' }}" 
            name="company_id" 
            class="form-control" 
            type="hidden">
        <input value="{{ $address->id ?? '' }}" 
            name="address_id" 
            class="form-control" 
            type="hidden">
        <div class="card-body">
            
            <div class="card">
                <div class="card-header">
                    <h4>@lang('User information')</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group mb-0">
                                <label for="first_name">@lang('First name') <span class="text-muted">(@lang('Required'))</span></label>
                                <input value="{{ $user->first_name }}" 
                                    name="first_name" 
                                    class="form-control" 
                                    type="text">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group mb-0">
                                <label for="last_name">@lang('Last name') <span class="text-muted">(@lang('Required'))</span></label>
                                <input value="{{ $user->last_name }}" 
                                    name="last_name" 
                                    class="form-control" 
                                    type="text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>@lang('User address')</h4>
                </div>
                <div class="card-body">

                    <div class="row">

                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label>@lang('Country')</label>
                                <select name="country" 
                                    class="form-control select2">
                                    <option value="">@lang('Select country')</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" 
                                            {{ $user->country_id == $country->id 
                                                 ? 'selected' 
                                                 : ''  }}>
                                            {{ $country->name }}
                                        </option>
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
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>@lang('User company')</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="company_name">@lang('Company')</label>
                                <input value="{{ $company ? $company->name : '' }}" 
                                    name="company_name" 
                                    class="form-control" 
                                    type="text">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="company_number">@lang('Company number')</label>
                                <input value="{{ $company ? $company->number : '' }}" 
                                    name="company_number" 
                                    class="form-control" 
                                    type="text">
                            </div>
                        </div>

                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="tax_number">@lang('Tax number')</label>
                                <input value="{{ $company ? $company->tax_number : '' }}"  
                                    name="tax_number" 
                                    class="form-control" 
                                    type="text">
                            </div>
                        </div>

                        <div class="col-12 col-md-12">
                            <div class="form-group mb-0">
                                <label for="phone">@lang('Phone')</label>
                                <input value="{{ $company ? $company->phone : '' }}" 
                                    name="phone" 
                                    class="form-control" 
                                    type="text">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card mb-0">
                <div class="card-header">
                    <h4>@lang('User extra details')</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="timezone">@lang('Timezone')</label>
                                <select name="timezone" class="form-control select2">
                                @foreach($timezones as $key => $value)
                                    <option value="{{ $key }}|{{ $value }}"
                                        {{ 
                                            $user->timezone_display == $key
                                            ? 'selected' 
                                            : ''
                                        }}>{!! $key !!}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="date_format">@lang('Date format')</label>
                                <select name="date_format" class="form-control select2">
                                    @foreach($dateFormat as $format)
                                        <option value="{{ $format }}"
                                            {{ $user 
                                                ? $user->date_format == $format 
                                                    ? 'selected' 
                                                    : '' 
                                                : '' }}>{{ $format }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="time_format">@lang('Time format')</label>
                                <select name="time_format" class="form-control select2">
                                    @foreach($timeFormat as $key => $format)
                                        <option value="{{ $key }}"
                                            {{ $user 
                                                ? $user->time_format == $key 
                                                    ? 'selected' 
                                                    : '' 
                                                : '' }}>{{ $format }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="language">@lang('Language')</label>
                                <select name="language" class="form-control select2">
                                    @foreach($languages as $language)
                                        <option value="{{ $language->code }}"
                                            {{ $user 
                                                ? $user->locale == $language->code 
                                                    ? 'selected' 
                                                    : '' 
                                                : '' }}>{{ $language->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

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
            </div>

        </div>
        <div class="card-footer bg-whitesmoke">
            <button type="submit" class="btn btn-primary btn-lg float-right">@lang('Save changes')</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
        </div>
    </form>            
</div>