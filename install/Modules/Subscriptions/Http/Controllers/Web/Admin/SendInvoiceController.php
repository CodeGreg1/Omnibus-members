<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Users\Repositories\UserRepository;

class SendInvoiceController extends BaseController
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        parent::__construct();

        $this->users = $users;
    }

    public function handle($userId, $paymentId)
    {
        $this->authorize('admin.subscriptions.users.invoices.send');

        $user = $this->users->findOrFail($userId);

        $user->sendInvoice($paymentId);
        return $this->successResponse(__(
            'Invoice successfully send to :user',
            ['user' => $user->full_name]
        ));
    }
}