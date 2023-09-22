<div class="card card-form" id="details">
    <div class="card-header">
        <img alt="image" class="mr-3 rounded-circle profile-avatar" width="40" src="{{ auth()->user()->present()->avatar }}">

        <button href="javascript:void(0)" 
            class="btn btn-primary" 
            data-toggle="modal" 
            data-target="#general-profile-upload-photo">@lang('Update photo')</button>
        <a href="javascript:void(0)" 
            class="btn btn-warning ml-2"
            id="btn-remove-photo">@lang('Remove photo')</a>
    </div>

    <form id="profile-details-form" method="post">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="first_name">@lang('First name') </label>
                        <input value="{{ auth()->user()->first_name }}" 
                            name="first_name" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="last_name">@lang('Last name') </label>
                        <input value="{{ auth()->user()->last_name }}" 
                            name="last_name" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-whitesmoke">
            <button type="submit" class="btn btn-primary btn-lg float-right">@lang('Save changes')</button>
        </div>
    </form>            
</div>