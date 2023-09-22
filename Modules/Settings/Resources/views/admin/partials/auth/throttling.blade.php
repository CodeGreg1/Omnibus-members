<div class="card">
                            
    <div class="card-header">
        <h4>@lang('Throttling')</h4>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-12 col-md-12">
                <div class="row">
                    <div class="col-12 col-md-12">

                        <div class="form-group">
                            <div>
                                <label for="throttle_login">@lang('Throttle login') <span class="text-muted">(@lang('Required'))</span></label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="throttle_login1" name="throttle_login" class="custom-control-input" value="1" {{ setting('throttle_login') == 1 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="throttle_login1">@lang('Enable')</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="throttle_logi2" name="throttle_login" class="custom-control-input" value="0" {{ setting('throttle_login') == 0 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="throttle_logi2">@lang('Disable')</label>
                            </div>
                            <small class='form-text text-muted form-help'>@lang('Should the system throttle login attempts?')</small>
                        </div>
                        
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label for="auth_throttle_maximum_attempts">@lang('Maximum attempts')  <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" name="auth_throttle_maximum_attempts" class="form-control" value="{{ setting('auth_throttle_maximum_attempts', 3) }}">
                            <small class='form-text text-muted form-help'>@lang('Maximum number of incorrect login attempts before the lockout.')</small>
                        </div>
                    </div>

                    <div class="col-12 col-md-12">
                        <div class="form-group mb-0">
                            <label for="auth_throttle_lockout_time">@lang('Lockout time')  <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" name="auth_throttle_lockout_time" class="form-control" value="{{ setting('auth_throttle_lockout_time', 2) }}">
                            <small class='form-text text-muted form-help'>@lang('Number of minutes to lock the user after a maximum of incorrect login attempts.')</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        
    </div>

</div>