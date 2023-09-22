<?php

namespace Modules\Subscriptions\Http\Controllers\Web\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Users\Repositories\UserRepository;

class SendInvoiceReceiptController extends BaseController
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        parent::__construct();

        $this->users = $users;
    }

    public function handle($userId, $paymentId)
    {
        $user = $this->users->findOrFail($userId);

        $user->sendInvoiceReceipt($paymentId);

        return $this->successResponse(__(
            'Invoice receipt successfully send to :user',
            ['user' => $user->full_name]
        ));
    }
}