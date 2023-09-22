<div class="modal fade" tabindex="-1" role="dialog" id="admin-page-cta-section-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('CTA section data')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="admin-page-cta-section-form">
                    <input type="hidden" name="id" />
                    <div class="form-group">
                        <label for="label">@lang('Button label')</label>
                        <input type="text" class="form-control" name="label" placeholder="@lang('Read more')">
                    </div>
                    <div class="form-group">
                        <label for="label">@lang('Button link')</label>
                        <input type="text" class="form-control" name="link" placeholder="https://example.com">
                    </div>
                    <div class="form-group">
                        <label class="custom-switch pl-0">
                            <input type="checkbox" name="new_tab" class="custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">@lang('Open new tab on click')</span>
                        </label>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-primary btn-save-page-cta-section-data">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
