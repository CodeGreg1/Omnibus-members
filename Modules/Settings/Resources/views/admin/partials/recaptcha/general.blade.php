<div class="card mb-0">
                            
    <div class="card-header">
        <h4>@lang('reCaptcha Api Settings')</h4>
    </div>
    <div class="card-body">

        <div class="alert alert-info">
            <p>@lang('To get API key kindly register your website here') <a href="https://google.com/recaptcha/admin/create" target="_blank">google.com/recaptcha/admin/create</a> </p>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="recaptcha_site_key">@lang('reCaptcha site key')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="recaptcha_site_key" class="form-control" value="{{ protected_data(setting('recaptcha_site_key'), 'reCaptcha site key') }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="form-group mb-0">
                    <label for="recaptcha_secret_key">@lang('reCaptcha secret key')  <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" name="recaptcha_secret_key" class="form-control" value="{{ protected_data(setting('recaptcha_secret_key'), 'reCaptcha secret key') }}">
                </div>
            </div>
        </div>


        
    </div>

</div>