<div class="modal fade" tabindex="-1" role="dialog" id="duplicate-page-modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Duplicate page')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="admin-page-form" id="admin-page-duplicate-form" method="post" data-route="">
                    <input type="hidden" name="view_edit" value="0">
                    <div class="form-group">
                        <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                        <input type="text"
                            name="name"
                            class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="description">@lang('Description') <span class="text-muted">(@lang('Optional'))</span></label>
                        <textarea name="description"
                            class="form-control"></textarea>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <div class="btn-group dropdown admin-duplicate-page-actions">
                            <button type="button" class="btn btn-primary " id="btn-admin-save-duplicate-page">@lang('Create page')</button>
                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                                </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" id="btn-admin-save-edit-duplicate-page">@lang('Create and edit')</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
