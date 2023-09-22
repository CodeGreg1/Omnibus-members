<div class="modal fade" tabindex="-1" role="dialog" id="admin-upload-photo-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Upload photos')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="admin-upload-photos-form">
                    <div class="form-group">
                        <label>@lang('Folder')</label>
                        <select class="form-control" name="folder">
                            <option value="" selected>@lang('Select folder')</option>
                            @foreach ($folders as $folder)
                                <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photos">@lang('Photos')</label>
                        <ul class="list-group photo-upload-images">
                        </ul>
                        <input type="file" name="photos[]" class="d-none" accept="image/jpeg,image/jpg,image/gif,image/png" multiple>
                        <button type="button" class="btn-add-photo-upload">@lang('Add photo')</button>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary btn-upload-photos">@lang('Upload')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
