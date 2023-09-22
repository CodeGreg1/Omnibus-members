<div class="modal fade" tabindex="-1" role="dialog" id="admin-page-text-media-section-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Text media section data')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="admin-page-text-media-section-form">
                    <input type="hidden" name="id" />
                    <div class="form-group">
                        <div class="control-label">@lang('Media position')</div>
                        <div class="custom-switches-stacked mt-2">
                            <label class="custom-switch pl-0">
                                <input type="radio" name="left" value="1" class="custom-switch-input">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">@lang('Left')</span>
                            </label>
                            <label class="custom-switch pl-0">
                                <input type="radio" name="left" value="0" class="custom-switch-input">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">@lang('Right')</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Media source')</label>
                        <select class="form-control" name="source_type">
                            <option value="image">@lang('Photo collection')</option>
                            <option value="embedded">@lang('Iframe embedded video source')</option>
                        </select>
                    </div>
                    <div class="form-group media-source-image">
                        <label for="media_source">@lang('Media')</label>
                        <div data-image-gallery="text-media-image" name="media_id"></div>
                    </div>
                    <div class="form-group media-source-embedded d-none">
                        <label for="label">@lang('Media url')</label>
                        <input type="text" class="form-control" name="media_embed_source" placeholder="https://www.youtube.com/embed/sample-video">
                    </div>
                    <div class="form-group">
                        <div class="control-label">@lang('Text alignment')</div>
                        <div class="custom-switches-stacked mt-2">
                            <label class="custom-switch pl-0">
                                <input type="checkbox" name="text_center" class="custom-switch-input">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">@lang('Align center')</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="items">@lang('Paragraphs')</label>
                        <ul class="data-list" id="section-text-media-data">
                        </ul>
                        <button type="button" class="btn btn-sm btn-success btn-block mt-2 btn-section-text-media-add-data-item">@lang('Add paragraph')</button>
                    </div>

                    <div class="form-group">
                        <label for="items">@lang('Checkmarks')</label>
                        <ul class="data-list" id="section-text-checkmarks">
                        </ul>
                        <button type="button" class="btn btn-sm btn-success btn-block mt-2 btn-section-text-media-add-checkmark-item">@lang('Add item')</button>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-primary btn-save-page-text-media-section-data">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
