<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Repositories\AddressRepository;
use Modules\Profile\Events\ProfileAddressUpdated;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Http\Requests\UpdateProfileAddressRequest;

class UpdateProfileAddressController extends BaseController
{
    /**
     * @var AddressRepository $addresses
     */
    protected $addresses;

    /**
     * @param AddressRepository $addresses
     */
    public function __construct(AddressRepository $addresses) 
    {
        $this->addresses = $addresses;

        parent::__construct();
    }

    /**
     * Update profile details
     * 
     * @param UpdateProfileAddressRequest $request
     * 
     * @return JsonResponse
     */
    public function update(UpdateProfileAddressRequest $request)
    {   
        if ( $request->get('address_id') != "" && !auth()->user()->addresses->contains($request->get('address_id')) ) {
            return $this->errorResponse(__('Address is not associated with this account. Please reload your page.'));
        }

        $address = $this->handleUpdatingAddress($request);
        
        $address->users()->sync([auth()->id()]);

        event(new ProfileAddressUpdated());

        return $this->successResponse(__('Address profile updated successfully.'));
    }

    /**
     * Handle updating account address
     * 
     * @param Request $request
     * 
     * @return AddressRepository
     */
    protected function handleUpdatingAddress($request) 
    {
        auth()->user()->update([
            'country_id' => request('country')
        ]);

        return $this->addresses->updateOrCreate([
            'id' => $request->get('address_id')
        ],$request->addressData());
    }
}
