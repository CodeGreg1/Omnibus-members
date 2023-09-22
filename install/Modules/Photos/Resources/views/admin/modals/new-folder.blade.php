<div class="modal fade" tabindex="-1" role="dialog" id="photo-new-folder-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Create new folder')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="photo-new-folder-form">
                    <div class="form-group">
                        <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group">
                        <label for="description">@lang('Description') <span class="text-muted">(@lang('Optional'))</span></label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">@lang('Create folder')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
