<div class="card mb-0">
                            
    <div class="card-header">
        <h4>@lang('Social Login')</h4>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-6 col-md-6">

                @if(setting('services_facebook_app_id') != '' && setting('services_facebook_app_secret') != '')
                <div class="form-group">
                    <div>
                        <label for="services_login_with_facebook">@lang('Login with Facebook') <span class="text-muted">(@lang('Required'))</span></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="services_login_with_facebook1" name="services_login_with_facebook" class="custom-control-input" value="1" {{ setting('services_login_with_facebook') == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="services_login_with_facebook1">@lang('Enable')</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="services_login_with_facebook2" name="services_login_with_facebook" class="custom-control-input" value="0" {{ setting('services_login_with_facebook') == 0 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="services_login_with_facebook2">@lang('Disable')</label>
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <label for="services_facebook_app_id">@lang('App ID')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="services_facebook_app_id" class="form-control" value="{{ protected_data(setting('services_facebook_app_id'), 'app id') }}">
                </div>

                <div class="form-group">
                    <label for="services_facebook_app_secret">@lang('App Secret')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="services_facebook_app_secret" class="form-control" value="{{ protected_data(setting('services_facebook_app_secret'), 'app secret') }}">
                </div>

                <div class="form-group mb-0">
                    <label for="services_facebook_redirect">@lang('Callback URL')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="services_facebook_redirect" class="form-control" value="{{ setting('services_facebook_redirect') }}" readonly>
                </div>
                
            </div>


            <div class="col-6 col-md-6">

                @if(setting('services_google_client_id') != '' && setting('services_google_client_secret') != '')
                <div class="form-group">
                    <div>
                        <label for="services_login_with_google">@lang('Login with Google') <span class="text-muted">(@lang('Required'))</span></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="services_login_with_google1" name="services_login_with_google" class="custom-control-input" value="1" {{ setting('services_login_with_google') == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="services_login_with_google1">@lang('Enable')</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="services_login_with_google2" name="services_login_with_google" class="custom-control-input" value="0" {{ setting('services_login_with_google') == 0 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="services_login_with_google2">@lang('Disable')</label>
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <label for="services_google_client_id">@lang('Client ID')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="services_google_client_id" class="form-control" value="{{ protected_data(setting('services_google_client_id'), 'client id') }}">
                </div>

                <div class="form-group">
                    <label for="services_google_client_secret">@lang('Client Secret')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="services_google_client_secret" class="form-control" value="{{ protected_data(setting('services_google_client_secret'), 'client secret') }}">
                </div>

                <div class="form-group mb-0">
                    <label for="services_google_redirect">@lang('Callback URL')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="services_google_redirect" class="form-control" value="{{ setting('services_google_redirect') }}"
                    readonly>
                </div>
                
            </div>

        </div>


        
    </div>

</div>