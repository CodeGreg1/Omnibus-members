<div class="modal fade" tabindex="-1" role="dialog" id="create-package-extra-condition-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Create extra condition')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="create-package-extra-condition-form" data-route="{{ route('admin.subscriptions.packages.extra-conditions.store', $package) }}">
                    <div class="form-group">
                        <label>@lang('Name')</label>
                        <input type="text" id="name" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>@lang('Description')<span class="text-muted">(@lang('Optional'))</span></label>
                        <input type="text" id="description" name="description" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>@lang('Shortcode')</label>
                        <input type="text" id="shortcode" name="shortcode" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>@lang('Value')</label>
                        <input type="text" id="value" name="value" class="form-control">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-primary" id="btn-create-package-extra-condition">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
