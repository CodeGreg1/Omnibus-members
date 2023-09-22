<div class="modal fade" tabindex="-1" role="dialog" id="admin-page-hero-section-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Hero section data')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="admin-page-hero-section-form">
                    <input type="hidden" name="id" />
                    <div class="form-group">
                        <label for="description">@lang('Buttons')</label>
                        <ul class="data-list" id="section-hero-buttons">

                        </ul>
                        <button type="button" class="btn btn-sm btn-success btn-block mt-2 btn-section-hero-add-button">@lang('Add button')</button>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-primary btn-save-page-hero-section-data">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>