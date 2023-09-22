@extends('auth::layouts.app-master')
    
@section('content')
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card card-form" id="password">
                <div class="card-header">
                    <h4><i class="fas fa-key"></i> @lang('Change Password')</h4>
                </div>

                <form id="profile-password-form" method="post">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <p class="alert alert-warning">@lang('To continue please change your default password for security reasons.')</p>
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
                                <div class="form-group">
                                    <label for="old_password">@lang('Old password') </label>
                                    <input name="old_password" 
                                        class="form-control" 
                                        type="password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
                    </div>
                </form>
                                  
            </div>
        </div>
    </div>
@endsection