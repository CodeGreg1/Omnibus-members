<?php

namespace Modules\Users\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Users\Support\UserStatus;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Users\Traits\ManageUserWalletStatus;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Users\Traits\ManageUserSubscriptionStatus;

class UsersDatatableController extends BaseController
{
    use ManageUserSubscriptionStatus, ManageUserWalletStatus;

    /**
     * @var UserRepository
     */
    protected $users;
    
    /**
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users) 
    {
        $this->users = $users;

        parent::__construct();
    }

    /**
     * Display users list
     * 
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('admin.users.datatable');
        
        $query = $this->users
            ->with([
                'company', 
                'roles'
            ]);

        if(request()->has('status') && request('status') == 'Active') {
            $query = $query->where('status', UserStatus::ACTIVE)->whereNotNull('email_verified_at');
        }

        if(request()->has('status') && request('status') == 'Banned') {
            $query = $query->where('status', UserStatus::BANNED);
        }

        if(request()->has('status') && request('status') == 'Unconfirmed') {
            $query = $query->where('status', UserStatus::ACTIVE)->whereNull('email_verified_at');
        }

        if(request()->has('role')) {
            $query = $query->whereHas('roles', function($query) {
                 $query->where('id', request('role'));
            });
        }

        if(request()->has('subscriptions')) {
            $query = $this->userSubscriptionStatus(
                $query,
                request()->get('subscriptions')
            );
        }

        if (request()->has('wallet')) {
            $query = $this->userWalletStatus(
                $query,
                request()->get('wallet')
            );
        }

        return DataTables::eloquent($query)
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                }
            })
            ->editColumn('email', function($row) {
                if(env('APP_DEMO')) {
                    return protected_data($row->email, 'email');
                }
                return $row->email;
            })
            ->editColumn('username', function($row) {
                if(env('APP_DEMO')) {
                    return protected_data($row->username, 'username');
                }
                return $row->username;
            })
            ->toJson();
    }
}
