<div class="card card-form" id="language">
    <div class="card-header">
        <h4><i class="fas fa-language"></i> @lang('Preferred language')</h4>
    </div>
    <form id="profile-language-form" method="post">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group mb-0">
                        <label for="locale">@lang('Language') </label>
                        <select name="locale" class="form-control select2">
                            @foreach($languages as $language)
                                <option value="{{ $language->code }}"
                                    {{ $user 
                                        ? $user->locale == $language->code 
                                            ? 'selected' 
                                            : '' 
                                        : '' }}>{{ $language->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-whitesmoke">
            <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
        </div>    
    </form>              
</div>