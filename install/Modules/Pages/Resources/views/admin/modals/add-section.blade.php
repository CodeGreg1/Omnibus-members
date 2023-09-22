<div class="modal fade" tabindex="-1" role="dialog" id="add-page-section-modal" data-keyboard="false"
    data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add Section')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center">
                    <ul class="page-section-items">
                        @foreach ($sections as $section)
                            <li class="border-bottom-0">
                                <div class="page-section-item-btn" data-template="{{ $section->template }}"
                                    data-name="{{ $section->name }}" data-has-data="{{ $section->has_data }}">
                                    <img class="page-section-item-btn-img" src="{{ $section->image }}"
                                        alt="{{ $section->name }}">
                                    <span>{{ __($section->name) }}</span>
                                </div>
                            </li>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</div>
