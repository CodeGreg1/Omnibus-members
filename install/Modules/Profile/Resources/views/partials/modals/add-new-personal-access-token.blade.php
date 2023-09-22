<div class="modal fade" role="dialog" id="add-new-personal-token-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Create New Token')</h5>
            </div>

            <div class="modal-body">
                <form id="add-new-personal-token-form" method="post">
                    
                    <div class="row">

                        <div class="col-12 col-md-12">

                            <div class="form-group">
                                <label for="token_name">@lang('Token name') <span class="text-muted">(@lang('Required'))</span></label>
                                <input type="text"
                                    name="token_name"
                                    class="form-control" required>
                            </div>
                        </div>

                    </div>
                    
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                        <button class="btn btn-primary float-right" type="submit">@lang('Save')</button>
                    </div>
                </form>
            </div>
             
        </div>
    </div>
</div>