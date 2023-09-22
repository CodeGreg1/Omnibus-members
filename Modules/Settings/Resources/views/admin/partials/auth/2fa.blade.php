<div class="card">
                            
    <div class="card-header">
        <h4>@lang('Two-factor authentication')</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="alert alert-info">
                <p>To get Authy key kindly register here and create your authy app <a href="https://console.twilio.com/" target="_blank">console.twilio.com/</a> </p>
            </div>
            <div class="col-6 col-md-6">

                @if(setting('services_authy_key') != '')
                <div class="form-group">
                    <div>
                        <label for="two_factor">@lang('Two-factor') <span class="text-muted">(@lang('Required'))</span></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="two_factor1" name="two_factor" class="custom-control-input" value="1" {{ setting('two_factor') == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="two_factor1">@lang('Enable')</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="two_factor2" name="two_factor" class="custom-control-input" value="0" {{ setting('two_factor') == 0 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="two_factor2">@lang('Disable')</label>
                    </div>
                    <small class='form-text text-muted form-help'>@lang('Two-Factor Authentication for the application.')</small>
                </div>
                @endif

                <div class="form-group mb-0">
                    <label for="services_authy_key">@lang('Authy Key')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="services_authy_key" class="form-control" value="{{ protected_data(setting('services_authy_key'), 'authey key') }}">
                </div>
                
            </div>

        </div>


        
    </div>

</div>