<div class="modal fade" tabindex="-1" role="dialog" id="admin-page-blog-section-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Blog section data')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="admin-page-blog-section-form">
                    <input type="hidden" name="id" />
                    <div class="form-group">
                        <label for="label">@lang('Total blogs to display')</label>
                        <input type="number" class="form-control" name="count" placeholder="3">
                    </div>
                    <div class="form-group">
                        <label>@lang('Blogs sort by')</label>
                        <select class="form-control" name="sort">
                            <option value="latest">@lang('Latest')</option>
                            <option value="oldest">@lang('Oldest')</option>
                        </select>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-primary btn-save-page-blog-section-data">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
