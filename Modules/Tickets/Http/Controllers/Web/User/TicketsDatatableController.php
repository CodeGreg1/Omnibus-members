<?php

namespace Modules\Tickets\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Modules\Tickets\Support\TicketStatus;
use Modules\Tickets\Support\TicketPriority;
use Illuminate\Contracts\Support\Renderable;
use Modules\Tickets\Repositories\TicketsRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class TicketsDatatableController extends BaseController
{   
    /**
     * @var TicketsRepository $tickets
     */
    protected $tickets;

    /**
     * @param TicketsRepository $tickets
     * 
     * @return void
     */
    public function __construct(TicketsRepository $tickets) 
    {
        $this->tickets = $tickets;

        parent::__construct();
    }

    /**
     * Handle datatable data
     * @return JsonResponse
     */
    public function index()
    {   
        $this->authorize('user.tickets.datatable');
        
        $query = $this->tickets->getModel()
            ->query()
            ->select('tickets.*')
            ->with([
                'user', 
                'category'
            ]);

        if(request()->has('sortValue')) {
            $sortValue = explode('__', request('sortValue'));

            if($sortValue[0] == 'category') {
                $query = $query->join(
                    'categories', 
                    'categories.id', 
                    '=', 
                    'tickets.category_id'
                )->orderBy('categories.name', $sortValue[1]);
            }
        }

        if(request()->has('status') && request('status') == 'Trashed') {
            $query = $query->onlyTrashed();
        }

        if(request()->has('status') && array_key_exists(request('status'), TicketStatus::lists())) {
            $query = $query->where('status', request('status'));
        }

        if(request()->has('priority') && array_key_exists(request('priority'), TicketPriority::lists())) {
            $query = $query->where('priority', request('priority'));
        }

        $query = $query->whereNotNull('user_id');

        return DataTables::eloquent($query)
            ->order(function ($query) {
                if (request()->has('sortValue')) {
                    $sortValue = explode('__', request('sortValue'));
                    
                    if($sortValue[0] != 'category') {
                        $query->orderBy($sortValue[0], $sortValue[1]);
                    }
                }
            })
            ->toJson();
    }
}
