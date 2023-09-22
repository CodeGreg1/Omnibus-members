<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <label class="custom-switch pl-0 mr-2">
                    <input type="radio" name="storage_driver" value="local" class="custom-switch-input" {{ setting('storage_driver', 'local') === 'local' ? 'checked' : '' }}>
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description"><h4>@lang('Local')</h4></span>
                </label>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card mb-0">
            <div class="card-header">
                <label class="custom-switch pl-0 mr-2">
                    <input type="radio" name="storage_driver" value="s3" class="custom-switch-input" {{ setting('storage_driver', 'local') === 's3' ? 'checked' : '' }}>
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description"><h4>@lang('Amazon S3')</h4></span>
                </label>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>@lang('AWS access key id') <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" class="form-control" name="aws_access_key_id" value="{{ protected_data(setting('aws_access_key_id'), 'AWS access key id') }}">
                </div>
                <div class="form-group">
                    <label>@lang('AWS secret access key') <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" class="form-control" name="aws_secret_access_key" value="{{ protected_data(setting('aws_secret_access_key'), 'AWS secret access key') }}">
                </div>
                <div class="form-group">
                    <label>@lang('AWS default region') <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" class="form-control" name="aws_default_region" value="{{ setting('aws_default_region') }}">
                </div>
                <div class="form-group">
                    <label>@lang('AWS bucket') <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" class="form-control" name="aws_bucket" value="{{ setting('aws_bucket') }}">
                </div>
                <div class="form-group mb-0">
                    <label>@lang('AWS endpoint') <span class="text-muted">(@lang('Optional'))</span></label>
                    <input type="text" class="form-control" name="aws_endpoint" value="{{ setting('aws_endpoint') }}">
                </div>
            </div>
        </div>
    </div>
</div>
