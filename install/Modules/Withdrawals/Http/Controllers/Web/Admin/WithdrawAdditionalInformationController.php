<?php

namespace Modules\Withdrawals\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\Withdrawals\Events\WithdrawalRequestUpdated;
use Modules\Withdrawals\Repositories\WithdrawalsRepository;

class WithdrawAdditionalInformationController extends BaseController
{
    /**
     * @var WithdrawalsRepository
     */
    public $withdrawals;

    public function __construct(WithdrawalsRepository $withdrawals)
    {
        parent::__construct();

        $this->withdrawals = $withdrawals;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $trx
     * @return Renderable
     */
    public function update(Request $request, $trx)
    {
        $this->authorize('admin.withdrawals.update');

        $model = $this->withdrawals->where('trx', $trx)->firstOrFail();

        $this->withdrawals->update($model, $request->only(['note']));

        if ($request->hasFile('image')) {

            foreach ($request->file('image') as $entry) {
                if ($entry->isValid()) {
                    $model->addMedia($entry)->toMediaCollection('additional_image');
                }
            }
        }

        event(new WithdrawalRequestUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Additional information updated.'),
            route('admin.withdrawals.show', $model)
        );
    }

    /**
     * Handle on removing media
     *
     * @param Request $request
     *
     * @return void
     */
    public function removeMedia(Request $request)
    {
        $this->authorize('admin.withdrawals.remove-media');

        $media = Media::where('uuid', $request->get('uid'))->first();

        $media->delete();

        return $this->successResponse(__('Image deleted successfully.'));
    }
}
