<div class="modal fade" tabindex="-1" role="dialog" id="add-language-phrase-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add :module Language Phrase', ['module' => $module->name ?? ''])</h5>
            </div>
            <form id="add-language-phrase-form" method="post" data-action="{{ $addLanguagePhraseRoute }}">
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="name">@lang('Key')</label>
                                <input type="text" class="form-control" name="key" placeholder="@lang('Key')" data-action="{{ $autoTranslateAddingPhrase }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="name">@lang('English')</label>
                                <input type="text" class="form-control language-value-en language-value" name="value[en]" placeholder="@lang('English')">
                            </div>
                        </div>

                        @php
                            $numItems = count($languages);
                            $li = 0;
                        @endphp
                        @foreach($languages as $language)
                            <div class="col-12 col-md-12">
                                <div class="form-group {{ ++$li === $numItems ? 'mb-0' : ''}}">
                                    <label for="name">{{ $language->title }}</label>
                                    <input type="text" class="form-control language-value-{{ $language->code }} language-value" name="value[{{ $language->code }}]" placeholder="{{ $language->title }}">
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
