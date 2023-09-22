<div class="modal fade" tabindex="-1" role="dialog" id="admin-edit-commission-type-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Edit commission type')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="admin-edit-commission-type-form">
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label>@lang('Name')</label>
                        <input type="text" class="form-control" name="name" readonly value="Commision" disabled>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="active" id="active">
                            <label class="custom-control-label" for="active">@lang('Status')</label>
                        </div>
                    </div>

                    <div class="affilliate-commission-type-conditions">
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6>@lang('Levels')</h6>
                        <button type="button" class="btn btn-icon icon-left btn-sm btn-primary btn-add-commission-type-level">
                            <i class="fas fa-plus"></i>
                            @lang('Add level')
                        </button>
                    </div>
                    <div>
                        <table class="table mb-4 affiliate-commission-type-levels">
                            <thead>
                                <tr>
                                <th scope="col">@lang('Level')</th>
                                <th scope="col">@lang('Rate in %')</th>
                                <th scope="col" style="width:60px;">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
