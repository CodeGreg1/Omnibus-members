<div class="modal fade page-section-details-modal" tabindex="-1" role="dialog" id="admin-page-cta-section-details-edit-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Edit section details')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="admin-page-cta-section-details-edit-form">
                    <input type="hidden" name="id" />
                    <div class="form-group">
                        <label for="name">@lang('Heading')</label>
                        <input type="text" class="form-control" name="heading" placeholder="@lang('Heading')">
                    </div>
                    <div class="form-group">
                        <label for="name">@lang('Sub-Heading')</label>
                        <input type="text" class="form-control" name="sub_heading" placeholder="@lang('Sub-Heading')">
                    </div>
                    <div class="form-group">
                        <label for="description">@lang('Description')</label>
                        <textarea class="form-control confirm_page_message" name="description" placeholder="@lang('Description')"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">@lang('Background style')</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="background_style" value="primary" class="selectgroup-input">
                                <span class="selectgroup-button">@lang('Primary')</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="background_style" value="info" class="selectgroup-input">
                                <span class="selectgroup-button">@lang('Info')</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="background_style" value="danger" class="selectgroup-input">
                                <span class="selectgroup-button">@lang('Danger')</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="background_style" value="image" class="selectgroup-input">
                                <span class="selectgroup-button">@lang('Image')</span>
                            </label>
                        </div>
                    </div>

                    <input type="hidden" class="custom-control-input" name="background_color" value="">

                    <div class="display-none section-background-image-wrapper">
                        <div class="form-group">
                            <label for="background_image">@lang('Background Image')</label>
                            <div data-image-gallery="2222"></div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-primary btn-save-section-details">@lang('Save changes')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
