<div class="modal fade" tabindex="-1" role="dialog" id="edit-feature-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add New Feature')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-feature-form" data-route="">
                    <div class="form-group">
                        <label for="title">@lang('Title')</label>
                        <input type="text" class="form-control" id="title" name="title" required />
                    </div>
                    <div class="form-group">
                        <label for="description">
                            @lang('Description')
                            <span class="text-muted">(@lang('Optional'))</span>
                        </label>
                        <textarea class="form-control" id="description" rows="2" name="description"></textarea>
                    </div>

                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary" id="btn-update-feature">@lang('Save changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
