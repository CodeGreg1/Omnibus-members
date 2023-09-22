<?php

namespace Modules\Wallet\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Wallet\Events\WalletAdded;
use Modules\Wallet\Events\WalletDeducted;
use Illuminate\Contracts\Support\Renderable;
use Modules\Users\Repositories\UserRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Wallet\Http\Requests\AddWalletBalanceRequest;
use Modules\Wallet\Http\Requests\DeductWalletBalanceRequest;

class WalletController extends BaseController
{
    /**
     * @var UserRepository
     */
    public $users;

    public function __construct(UserRepository $users)
    {
        parent::__construct();

        $this->users = $users;
    }

    /**
     * Add user balance
     * @param AddWalletBalanceRequest $request
     * @return Renderable
     */
    public function addBalance(AddWalletBalanceRequest $request)
    {
        $user = $this->users->findOrFail($request->get('user'));
        $amount = floatval($request->get('amount'));
        $wallet = $user->addWallet($request->get('currency'), $amount);

        WalletAdded::dispatch($request->user(), $wallet, $amount);

        return $this->handleAjaxRedirectResponse(
            __('Successfully added money to balance.'),
            url()->previous()
        );
    }

    /**
     * Add user balance
     * @param DeductWalletBalanceRequest $request
     * @return Renderable
     */
    public function deductBalance(DeductWalletBalanceRequest $request)
    {
        $user = $this->users->findOrFail($request->get('user'));
        $amount = floatval($request->get('amount'));
        $wallet = $user->unLoadWallet(
            $user->getWalletByCurrency($request->get('currency')),
            $amount
        );

        WalletDeducted::dispatch($request->user(), $wallet, $amount);

        return $this->handleAjaxRedirectResponse(
            __('Successfully deducted money from balance.'),
            url()->previous()
        );
    }
}
