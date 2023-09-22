<div class="card mb-0">
                            
    <div class="card-header">
        <h4>@lang('Media Settings')</h4>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-6 col-md-6">

                <div class="form-group mb-0">
                    <div>
                        <label for="remember_me">@lang('Media conversions performed on?') <span class="text-muted">(@lang('Required'))</span></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="media_queue_conversions1" name="media_queue_conversions" class="custom-control-input" value="1" {{ setting('media_queue_conversions') == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="media_queue_conversions1">@lang('On queue')</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="media_queue_conversions2" name="media_queue_conversions" class="custom-control-input" value="0" {{ setting('media_queue_conversions') == 0 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="media_queue_conversions2">@lang('Not on queue')</label>
                    </div>
                    <small class='form-text text-muted form-help'>@lang('Note: If you choose "On queue" you must have :queue_jobs setup on your server. Using queue it will increase user performance.', ['queue_jobs' => '<b>Queue Jobs</b>'])</small>
                </div>
                
            </div>
        </div>


        
    </div>

</div>