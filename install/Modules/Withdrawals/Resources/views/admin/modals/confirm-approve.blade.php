<div class="modal fade" tabindex="-1" role="dialog" id="withdrawal-confirm-approve-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirm')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="withdrawal-confirm-approve-form">
                    <div class="form-group">
                        <label class="d-block" for="note">@lang('Note')</label>
                        <input type="text" name="note" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="image">@lang('Screenshot')</label>
                        <div class="dropzone dropzone-multiple" data-toggle="dropzone1" data-dropzone-url="http://" id="confirm-approve-withdrawal-image-dropzone"
                        data-field-name="image"
                        data-dropzone-multiple>
                            <div class="fallback">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="dropzone-1" name="image" multiple>
                                    <label class="custom-file-label" for="customFileUpload">{{__('Choose image')}}</label>
                                </div>
                            </div>
                            <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                                <li class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar">
                                                <img class="rounded" src="{{ url('upload/media/default/dr-default.png') }}" alt="Image placeholder" data-dz-thumbnail>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="text-sm mb-1" data-dz-name>...</h6>
                                            <p class="small text-muted mb-0" data-dz-size></p>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="dropdown-item" data-dz-remove>
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-light mr-2" data-dismiss="modal" aria-label="Close">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-primary" data-route="{{ route('admin.withdrawals.approve', $withdraw) }}">@lang('Approve now')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
