<div class="modal fade" role="dialog" id="edit-ticket-subject-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Edit subject')</h5>
            </div>
            <div class="modal-body">
                <form id="edit-ticket-subject-form" method="post" data-route="{{ route($editSubjectRoute, [encrypt($tickets->id)]) }}">
                    @method('patch')
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="description">@lang('Subject')</label>
                                <input type="text" 
                                    name="subject" 
                                    class="form-control"
                                    value="{{ ucwords($tickets->subject) }}">
                            </div>
                        </div>
                    </div>
                    <!-- End .row -->
                    
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Save Changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>