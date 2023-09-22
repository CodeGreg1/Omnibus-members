<div class="card card-form" id="timezone">
    <div class="card-header">
        <h4><i class="fas fa-clock"></i> @lang('Timezone and date & time formats')</h4>
    </div>
    
    <form id="profile-timezone-form" method="post">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="timezone">@lang('Timezone') </label>
                        <select name="timezone" class="form-control select2">
                            @foreach($timezones as $key => $value)
                                <option value="{{ $key }}|{{ $value }}"
                                    {{ 
                                        $user->timezone_display == $key 
                                        ? 'selected' 
                                        : ''
                                    }}>{!! $key !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group mb-0">
                        <label for="date_format">@lang('Date format') </label>
                        <select name="date_format" class="form-control select2">
                            @foreach($dateFormat as $format => $label)
                                <option value="{{ $format }}"
                                    {{ $user 
                                        ? $user->date_format == $format 
                                            ? 'selected' 
                                            : '' 
                                        : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group mb-0">
                        <label for="time_format">@lang('Time format') </label>
                        <select name="time_format" class="form-control select2">
                            @foreach($timeFormat as $key => $format)
                                <option value="{{ $key }}"
                                    {{ $user 
                                        ? $user->time_format == $key 
                                            ? 'selected' 
                                            : '' 
                                        : '' }}>{{ $format }}</option>
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