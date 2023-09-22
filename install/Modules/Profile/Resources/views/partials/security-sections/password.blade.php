<div class="card card-form" id="password">
    <div class="card-header">
        <h4><i class="fas fa-key"></i> @lang('Password')</h4>
    </div>

    <form id="profile-password-form" method="post">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <p>@lang('You last changed your password about :last_change.', ['last_change' => auth()->user()->present()->lastPasswordChange])</p>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="password">@lang('New password') </label>
                        <input name="password" 
                            class="form-control" 
                            type="password">
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="confirm_password">@lang('Confirm new password') </label>
                        <input name="confirm_password" 
                            class="form-control" 
                            type="password">
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group mb-0">
                        <label for="old_password">@lang('Old password') </label>
                        <input name="old_password" 
                            class="form-control" 
                            type="password">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-whitesmoke">
            <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Update Password')</button>
        </div>
    </form>
                      
</div>