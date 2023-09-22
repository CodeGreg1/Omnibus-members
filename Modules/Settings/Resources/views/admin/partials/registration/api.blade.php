<div class="card mb-0">
                            
    <div class="card-header">
        <h4>@lang('API Registration Settings')</h4>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-12 col-md-12">

                <div class="row">

                    <div class="col-6 col-md-6">

                        <div class="form-group mb-0">
                            @if(setting('recaptcha_site_key') != '' && setting('recaptcha_secret_key'))
                                <div>
                                    <label for="api_registration_captcha">@lang('Captcha validation') <span class="text-muted">(@lang('Required'))</span></label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="api_registration_captcha1" name="api_registration_captcha" class="custom-control-input" value="1" {{ setting('api_registration_captcha') == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="api_registration_captcha1">@lang('Enable')</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="api_registration_captcha2" name="api_registration_captcha" class="custom-control-input" value="0" {{ setting('api_registration_captcha') == 0 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="api_registration_captcha2">@lang('Disable')</label>
                                </div>
                                <small class='form-text text-muted form-help'>@lang('Require the captcha in API registration.')</small>
                            @else
                                <div class="alert alert-warning">
                                    @lang('To enable reCaptcha validation in your registration you need to add API credentials :link', ['link' => '<a href="/admin/settings/recaptcha">here.</a>']) 
                                </div>
                            @endif
                        </div>
                        
                    </div>


                </div>


            </div>

        </div>


        
    </div>

</div>