<div class="modal fade" tabindex="-1" role="dialog" id="edit-package-extra-condition-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Edit extra condition')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-package-extra-condition-form" data-route="">
                    <input type="hidden" name="id">
                    <input type="hidden" name="package_id">
                    <div class="form-group">
                        <label>@lang('Name')</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>@lang('Description')<span class="text-muted">(@lang('Optional'))</span></label>
                        <input type="text" name="description" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>@lang('Shortcode')</label>
                        <input type="text" name="shortcode" class="form-control" disabled="disabled">
                    </div>
                    <div class="form-group">
                        <label>@lang('Value')</label>
                        <input type="text" name="value" class="form-control">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-primary" id="btn-update-package-extra-condition">@lang('Save changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
