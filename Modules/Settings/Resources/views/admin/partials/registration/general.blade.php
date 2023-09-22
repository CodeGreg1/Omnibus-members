<div class="card">
                            
    <div class="card-header">
        <h4>@lang('General Settings')</h4>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-12 col-md-12">
                <div class="row">
                    <div class="col-6 col-md-6">

                        <div class="form-group">
                            <div>
                                <label for="allow_registration">@lang('Allow registration') <span class="text-muted">(@lang('Required'))</span></label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="allow_registration1" name="allow_registration" class="custom-control-input" value="1" {{ setting('allow_registration') == 1 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="allow_registration1">@lang('Enable')</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="allow_registration2" name="allow_registration" class="custom-control-input" value="0" {{ setting('allow_registration') == 0 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="allow_registration2">@lang('Disable')</label>
                            </div>
                            <small class='form-text text-muted form-help'>@lang('Allow new registration for the application.')</small>
                        </div>
                        
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-md-6">
                        <div class="form-group">
                            <label for="registration_role">@lang('Role for new registrations')  <span class="text-muted">(@lang('Required'))</span></label>
                            <select name="registration_role" class="form-control select2">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ 
                                            setting('registration_role') == $role->id 
                                            ? 'selected' 
                                            : ''
                                        }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <small class='form-text text-muted form-help'>@lang('User role assign upon registration.')</small>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="form-group">
                            <label for="registration_path_redirect_to">@lang('Redirect to path after registered')  <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" name="registration_path_redirect_to" class="form-control" value="{{ setting('registration_path_redirect_to', '/') }}">
                            <small class='form-text text-muted form-help'>@lang('Redirected path after successfully registered.')</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-md-6">

                        <div class="form-group">
                            <div>
                                <label for="registration_tos">@lang('Terms & conditions') <span class="text-muted">(@lang('Required'))</span></label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="registration_tos1" name="registration_tos" class="custom-control-input" value="1" {{ setting('registration_tos') == 1 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="registration_tos1">@lang('Enable')</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="registration_tos2" name="registration_tos" class="custom-control-input" value="0" {{ setting('registration_tos') == 0 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="registration_tos2">@lang('Disable')</label>
                            </div>
                            <small class='form-text text-muted form-help'>@lang('The user has to confirm that should agree to terms and conditions before can continue to create an account.')</small>
                        </div>
                        
                    </div>

                    <div class="col-6 col-md-6">

                        <div class="form-group">
                            <div>
                                <label for="registration_email_confirmation">@lang('Email confirmation') <span class="text-muted">(@lang('Required'))</span></label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="registration_email_confirmation1" name="registration_email_confirmation" class="custom-control-input" value="1" {{ setting('registration_email_confirmation') == 1 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="registration_email_confirmation1">@lang('Enable')</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="registration_email_confirmation2" name="registration_email_confirmation" class="custom-control-input" value="0" {{ setting('registration_email_confirmation') == 0 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="registration_email_confirmation2">@lang('Disable')</label>
                            </div>
                            <small class='form-text text-muted form-help'>@lang('Require email confirmation for newly registered users.')</small>
                        </div>
                        
                    </div>

                    <div class="col-6 col-md-6">

                        <div class="form-group mb-0">
                            @if(setting('recaptcha_site_key') != '' && setting('recaptcha_secret_key'))
                                <div>
                                    <label for="registration_captcha">@lang('Captcha validation') <span class="text-muted">(@lang('Required'))</span></label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="registration_captcha1" name="registration_captcha" class="custom-control-input" value="1" {{ setting('registration_captcha') == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="registration_captcha1">@lang('Enable')</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="registration_captcha2" name="registration_captcha" class="custom-control-input" value="0" {{ setting('registration_captcha') == 0 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="registration_captcha2">@lang('Disable')</label>
                                </div>
                                <small class='form-text text-muted form-help'>@lang('Require the captcha in registration.')</small>
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