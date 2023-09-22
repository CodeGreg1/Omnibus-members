<div class="card">
                            
    <div class="card-header">
        <h4>@lang('General Settings')</h4>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="app_name">@lang('App name') <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text"
                        name="app_name"
                        class="form-control"
                        value="{{ setting('app_name') }}" 
                        required>
                </div>
            </div>

            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="locale">@lang('Language') <span class="text-muted">(@lang('Required'))</span></label>
                    <select name="locale" class="form-control select2">
                        @foreach($languages as $languange)
                            <option 
                                value="{{ $languange->code }}"
                                {{ 
                                    $languange->code == setting('locale')
                                    ? 'selected' 
                                    : '' 
                                }}
                            >{{ $languange->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="timezone">@lang('Timezone')  <span class="text-muted">(@lang('Required'))</span></label>
                    <select name="timezone" class="form-control select2">
                        @foreach($timezones as $key => $value)
                            <option value="{{ $key }}|{{ $value }}"
                                {{ 
                                    setting('timezone_key_value') == $key . '|' . $value 
                                    ? 'selected' 
                                    : ''
                                }}>{{ $key }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="date_format">@lang('Date format')  <span class="text-muted">(@lang('Required'))</span></label>
                    <select name="date_format" class="form-control select2">
                        @foreach($dateFormats as $format => $label)
                            <option value="{{ $format }}"
                               {{  setting('date_format') == $format 
                                            ? 'selected' 
                                            : ''}}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="time_format">@lang('Time format')  <span class="text-muted">(@lang('Required'))</span></label>
                    <select name="time_format" class="form-control select2">
                        @foreach($timeFormats as $key=>$value)
                            <option value="{{ $key }}"
                                {{ 
                                    setting('time_format') == $key 
                                    ? 'selected' 
                                    : ''
                                }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="alert alert-info">
                    <p><i class="fas fa-info-circle"></i> @lang('Please update the currency default in the') <a href="/admin/currencies">@lang('currency list')</a>.</p>
                </div>
                <div class="form-group mb-0">
                    <label for="currency">@lang('Currency') <span class="text-muted">(@lang('Required'))</span></label>
                    <select name="currency" class="form-control select2" disabled>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->code }}"
                                {{ 
                                    setting('currency') == $currency->code 
                                    ? 'selected' 
                                    : ''
                                }}>{{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>


        
    </div>

</div>