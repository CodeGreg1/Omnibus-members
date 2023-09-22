<div class="modal fade" tabindex="-1" role="dialog" id="select-image-galley-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Photos')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-0">
                    <div class="d-flex justify-content-between align-items-baseline mb-3">
                        <label class="form-label mr-auto">@lang('Select photo')</label>
                        <div class="d-flex align-items-center">
                            <select class="form-control form-control-sm" name="gallery-folder-select">
                                <option value="all">@lang('All')</option>
                            </select>
                            <div class="btn-group" id="select-image-new-folder-dropdown">
                                <button type="button" class="btn btn-icon icon-left btn-primary dropdown-toggle ml-1 py-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div class="dropdown-menu py-3 px-4 pb-4" style="width: 260px;">
                                    <form id="photo-new-folder-form">
                                        <div class="form-group mb-2">
                                            <h6>
                                                @lang('New folder')
                                            </h6>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                                            <input type="text" class="form-control" name="name">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">@lang('Description') <span class="text-muted">(@lang('Optional'))</span></label>
                                            <textarea class="form-control" id="description" name="description"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary float-right">@lang('Create folder')</button>
                                    </form>
                                </div>
                            </div>
                            <div class="btn-group" id="select-image-upload-photos-dropdown">
                                <button type="button" class="btn btn-icon icon-left btn-primary dropdown-toggle ml-1 py-2 disabled" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled="true">
                                    <i class="fas fa-upload"></i>
                                </button>
                                <div class="dropdown-menu py-0" style="width: 220px;">
                                    <form class="px-4 pt-3 pb-4" id="photo-gallery-upload-form">
                                        <input type="file" name="photos[]" class="d-none" accept="image/jpeg,image/jpg,image/gif,image/png" multiple>
                                        <div class="form-group mb-2">
                                            <label>
                                                @lang('Upload photos')
                                            </label>
                                            <ul class="list-group photo-upload-images scrollable-menu">
                                            </ul>
                                        </div>

                                        <div class="d-flex justify-content-center">
                                            <button type="button" class="btn btn-sm btn-light ml-auto btn-add-photo-gallery-upload w-50">@lang('Add photo')</button>
                                            <button type="submit" class="btn btn-sm btn-primary ml-1 w-50">@lang('Upload')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center position-relative min-h-200px">
                        <div class="row gutters-sm w-100" id="image-gallery">
                        </div>
                        <div class="galley-image-loader">
                            <div class="gallery-photo-select-loader">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer pb-4">
                <nav aria-label="..." class="mr-auto photos-pagination">
                    <ul class="pagination mb-0">
                    </ul>
                </nav>
                <button type="button" class="btn btn-primary btn-image-gallery-select">@lang('Select')</button>
            </div>
        </div>
    </div>
</div>
