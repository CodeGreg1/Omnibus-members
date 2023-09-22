<div class="card">
                            
    <div class="card-header">
        <h4>@lang('General Settings')</h4>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-6 col-md-6">

                <div class="form-group">
                    <div>
                        <label for="remember_me">@lang('Remember me') <span class="text-muted">(@lang('Required'))</span></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="remember_me1" name="remember_me" class="custom-control-input" value="1" {{ setting('remember_me') == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="remember_me1">@lang('Enable')</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="remember_me2" name="remember_me" class="custom-control-input" value="0" {{ setting('remember_me') == 0 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="remember_me2">@lang('Disable')</label>
                    </div>
                    <small class='form-text text-muted form-help'>@lang('Display the "Remember me" in the login form.')</small>
                </div>
                
            </div>

            <div class="col-6 col-md-6">

                <div class="form-group">
                    <div>
                        <label for="forgot_password">@lang('Forget password') <span class="text-muted">(@lang('Required'))</span></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="forgot_password1" name="forgot_password" class="custom-control-input" value="1" {{ setting('forgot_password') == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="forgot_password1">@lang('Enable')</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="forgot_password2" name="forgot_password" class="custom-control-input" value="0" {{ setting('forgot_password') == 0 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="forgot_password2">@lang('Disable')</label>
                    </div>
                    <small class='form-text text-muted form-help'>@lang('Display the forgot password.')</small>
                </div>
                
            </div>

            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="remember_me_lifetime">@lang('Remember me lifetime')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="remember_me_lifetime" class="form-control" value="{{ setting('remember_me_lifetime', 30) }}">
                    <small class='form-text text-muted form-help'>@lang('Number of days the authenticated session remembered.')</small>
                </div>
            </div>

            <div class="col-6 col-md-6">
                <div class="form-group">
                    <label for="reset_token_lifetime">@lang('Reset token lifetime')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="auth_reset_token_lifetime" class="form-control" value="{{ setting('auth_reset_token_lifetime', 60) }}">
                    <small class='form-text text-muted form-help'>@lang('Number of minutes the reset token is valid.')</small>
                </div>
            </div>

            <div class="col-6 col-md-6">
                <div class="form-group mb-0">
                    <label for="auth_path_redirect_to">@lang('Authenticated path redirect to')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="auth_path_redirect_to" class="form-control" value="{{ setting('auth_path_redirect_to', '/dashboard') }}">
                    <small class='form-text text-muted form-help'>@lang('Redirected path after successfully login.')</small>
                </div>
            </div>

        </div>


        
    </div>

</div>