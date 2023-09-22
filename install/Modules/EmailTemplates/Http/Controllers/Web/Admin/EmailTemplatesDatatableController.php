<?php

namespace Modules\EmailTemplates\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\EmailTemplates\Repositories\EmailTemplateRepository;

class EmailTemplatesDatatableController extends BaseController
{
    /**
     * @var EmailTemplateRepository
     */
    protected $emailTemplates;

    public function __construct(EmailTemplateRepository $emailTemplates) 
    {
        $this->emailTemplates = $emailTemplates;
        
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index()
    {
        return DataTables::eloquent($this->emailTemplates->getModel()->query())
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    $query->orderBy($sortValue[0], $sortValue[1]);
                }
            })
            ->toJson();

    }
}
