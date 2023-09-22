<div class="modal fade" tabindex="-1" role="dialog" id="cron-not-running-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Warning!')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="alert alert-warning mb-0">@lang('Cron job has been inactive for more than an hour. Please double-check your setup!')</div>

                <p class="text-muted mb-0">* Cron jobs must be setup so that the system runs smoothly.</p>
                
            </div>
        </div>
    </div>
</div>