<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Repositories\AddressRepository;
use Modules\Profile\Events\ProfileCompanyUpdated;
use Modules\Profile\Repositories\CompanyRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Http\Requests\UpdateProfileCompanyRequest;

class UpdateProfileCompanyController extends BaseController
{   
    /**
     * @var AddressRepository $addresses
     */
    protected $addresses; 

    /**
     * @var CompanyRepository $addresses
     */
    protected $companies;

    /**
     * @param AddressRepository $addresses
     * @param CompanyRepository $companies
     */
    public function __construct(AddressRepository $addresses, CompanyRepository $companies) 
    {
        $this->addresses = $addresses;

        $this->companies = $companies;

        parent::__construct();
    }

    /**
     * Update profile details
     * 
     * @param UpdateProfileCompanyRequest $request
     * 
     * @return JsonResponse
     */
    public function update(UpdateProfileCompanyRequest $request)
    {   
        if ( auth()->user()->company && auth()->user()->company->id != $request->get('company_id') ) {
            return $this->errorResponse(__('Company is not associated with this account. Please reload your page.'));
        }

        $company = $this->handleUpdatingCompany($request);

        event(new ProfileCompanyUpdated());

        return $this->successResponse(__('Company profile updated successfully.'));
    }

    /**
     * Handle updating account company
     * 
     * @param Request $request
     * 
     * @return CompanyRepository
     */
    protected function handleUpdatingCompany($request) 
    {
        return $this->companies->updateOrCreate([
            'user_id' => auth()->id(),
            'id' => $request->get('company_id')
        ], $request->companyData());
    }
}
