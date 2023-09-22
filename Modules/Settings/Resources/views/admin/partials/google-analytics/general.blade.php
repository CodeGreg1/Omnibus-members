<div class="card mb-0">

    <div class="card-header">
        <h4>@lang('Google Analytics Settings')</h4>
    </div>
    <div class="card-body">

        <div class="alert alert-info">
            <p>@lang('To get code key kindly create your account here') <a href="https://analytics.google.com/analytics/web/provision/#/provision/create" target="_blank">analytics.google.com/analytics/web/provision/#/provision/create</a> </p>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="form-group mb-0">
                    <label for="google_nalytics_code">@lang('Google analytics code')  <span class="text-muted">(@lang('Optional'))</span></label>
                    <input type="text" name="google_nalytics_code" class="form-control" value="{{ setting('google_nalytics_code') }}">
                </div>
            </div>
        </div>

    </div>

</div>
