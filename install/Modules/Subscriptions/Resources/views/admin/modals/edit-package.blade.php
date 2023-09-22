<div class="modal fade" tabindex="-1" role="dialog" id="edit-package-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Edit package details')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-package-form" data-route="{{ route('admin.subscriptions.packages.update', $package) }}">
                    <div class="form-group">
                        <label for="name">@lang('Name')</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="@lang('Premium Plan')" value="{{ $package->name }}">
                    </div>
                    <div class="form-group">
                        <label for="primary_heading">@lang('Primary heading')</label>
                        <input type="text" class="form-control" name="primary_heading" id="primary_heading" value="{{ $package->primary_heading }}">
                    </div>
                    <div class="form-group">
                        <label for="secondary_heading">@lang('Secondary heading')</label>
                        <input type="text" class="form-control" name="secondary_heading" id="secondary_heading" value="{{ $package->secondary_heading }}">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-primary" id="btn-update-package">@lang('Save changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
