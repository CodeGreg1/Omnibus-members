<div class="modal fade" tabindex="-1" role="dialog" id="add-package-feature-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Features')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="save-new-package-features-form" data-href="{{ route('admin.subscriptions.packages.update-feature', $package) }}">
                    <ul class="list-unstyled list-unstyled-border">
                        @foreach ($packageFeatures as $feature)
                            <li class="pb-0 mb-2 border-bottom-0">
                                <div class="custom-control custom-checkbox">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        name="package-feature"
                                        id="feature-{{ $feature->id }}"
                                        data-id="{{ $feature->id }}"
                                        @checked(true)
                                    >
                                    <label class="custom-control-label" for="feature-{{ $feature->id }}">
                                        <strong>{{ $feature->title }}</strong>
                                    </label>
                                </div>
                            </li>
                        @endforeach
                        @foreach ($features as $feature)
                        <li class="pb-0 mb-2 border-bottom-0">
                            <div class="custom-control custom-checkbox">
                                <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    name="package-feature"
                                    id="feature-{{ $feature->id }}"
                                    data-id="{{ $feature->id }}"
                                >
                                <label class="custom-control-label" for="feature-{{ $feature->id }}">
                                    <strong>{{ $feature->title }}</strong>
                                </label>
                            </div>
                        </li>
                        @endforeach
                        @if (!count($packageFeatures) && !count($features))
                            <li class="no-feature-found">
                                <div class="d-flex justify-content-center align-items-center">
                                    <p class="text-muted py-4">No features found</p>
                                </div>
                            </li>
                        @endif
                    </ul>

                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
