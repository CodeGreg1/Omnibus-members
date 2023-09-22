<div class="card card-form" id="details">
    <div class="card-header">
        <h4>{{ $pageTitle }}</h4>
    </div>

    <form id="user-settings-form" method="post" data-action="{{ route('admin.users.update-user-settings', $user->id) }}">
        @method('patch')
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="status">@lang('User status') <span class="text-muted">(@lang('Required'))</span></label>
                        <select name="status" 
                            class="form-control select2">
                            <option value="">@lang('Select status')</option>
                            @foreach($userStatuses as $userStatus)
                                <option {{ $user->status == $userStatus 
                                         ? 'selected' 
                                         : ''  }}>
                                    {{ $userStatus }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="role">@lang('User role') <span class="text-muted">(@lang('Required'))</span></label>
                        <select name="role" 
                            class="form-control select2">
                            <option>@lang('Select role')</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ $userRole ?
                                            $userRole->id  == $role->id
                                                ? 'selected'
                                                : '' 
                                            : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="email">@lang('Email') <span class="text-muted">(@lang('Required'))</span></label>
                        <input value="{{ $user->email }}" 
                            name="email" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="username">@lang('Username')</label>
                        <input value="{{ $user->username }}" 
                            name="username" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label for="password">@lang('New password')</label>
                        <input name="password" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <div class="form-group mb-0">
                        <label for="confirm_password">@lang('Confirm new password')</label>
                        <input name="confirm_password" 
                            class="form-control" 
                            type="text">
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer bg-whitesmoke">
            <button type="submit" class="btn btn-primary btn-lg float-right">@lang('Save changes')</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
        </div>
    </form>
</div>