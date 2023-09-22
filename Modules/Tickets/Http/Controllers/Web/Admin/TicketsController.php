<?php

namespace Modules\Tickets\Http\Controllers\Web\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Illuminate\Support\Facades\Cache;
use Modules\Categories\Models\Category;
use Modules\Tickets\Support\TicketStatus;
use Modules\Tickets\Events\TicketsCreated;
use Modules\Tickets\Events\TicketsDeleted;
use Modules\Tickets\Events\TicketsReplied;
use Modules\Tickets\Events\TicketsUpdated;
use Modules\Tickets\Events\TicketsRestored;
use Illuminate\Contracts\Support\Renderable;
use Modules\CategoryTypes\Models\CategoryType;
use Modules\Tickets\Events\TicketsForceDeleted;
use Modules\Tickets\Events\TicketsStatusChanged;
use Modules\Tickets\Events\TicketsStatusUpdated;
use Modules\Tickets\Events\TicketsSubjectUpdated;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\Tickets\Repositories\TicketsRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\Categories\Repositories\CategoriesRepository;
use Modules\Tickets\Http\Requests\AdminReplyTicketRequest;
use Modules\Tickets\Http\Requests\AdminStoreTicketRequest;
use Modules\Tickets\Http\Requests\AdminUpdateTicketRequest;
use Modules\Tickets\Repositories\TicketConversationsRepository;
use Modules\Tickets\Http\Requests\AdminUpdateTicketConvoRequest;
use Modules\Tickets\Http\Requests\AdminUpdateTicketSubjectRequest;

class TicketsController extends BaseController
{   
    /**
     * @var TicketsRepository $tickets
     */
    protected $tickets;

    /**
     * @var TicketConversationsRepository $tickets
     */
    protected $ticketConversations;

    /**
     * @var CategoriesRepository $categories
     */
    protected $categories;

    /**
     * @var string $redirectTo
     */
    protected $redirectTo = '/admin/tickets';

    /**
     * @param TicketsRepository $tickets
     * 
     * @return void
     */
    public function __construct(TicketsRepository $tickets, TicketConversationsRepository $ticketConversations, CategoriesRepository $categories) 
    {
        $this->tickets = $tickets;
        $this->ticketConversations = $ticketConversations;
        $this->categories = $categories;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.tickets.index');

        return view('tickets::admin.index', [
            'pageTitle' => __('Tickets'),
            'policies' => JsPolicy::get('tickets'),
            'statusCounter' => ticket_counter()
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $number
     * @return Renderable
     */
    public function show($number)
    {
        $this->authorize('admin.tickets.show');

        return view('tickets::admin.show', [
            'pageTitle' => __('Show ticket'),
            'statuses' => TicketStatus::lists(),
            'tickets' => $this->tickets->where('number', $number)->with([
                'user', 
                'conversations' => function($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])->firstOrFail()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->authorize('admin.tickets.edit');

        $users = User::pluck('first_name', 'id');
        
        $categories = $this->categories->whereType(CategoryType::TYPE_SUPPORT);
        
        return view('tickets::admin.edit', [
            'pageTitle' => __('Edit ticket'),
            'tickets' => $this->tickets->findOrFail($id),
            'users' => $users,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param AdminUpdateTicketRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(AdminUpdateTicketRequest $request, $id)
    {
        $id = decrypt($id);

        $model = $this->tickets->findOrFail($id);

        $this->tickets
            ->update($model, 
                $request->only('message'));

        event(new TicketsUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Ticket updated successfully.'), 
            route('admin.tickets.show', $model->number)
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.tickets.delete');

        $model = $this->tickets->findOrFail(decrypt($request->id));

        $this->tickets->delete($model);
        
        event(new TicketsDeleted($model));
        
        return $this->handleAjaxRedirectResponse(
            __('Ticket deleted successfully.'), 
            route('admin.tickets.index')
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function multiDestroy(Request $request)
    {
        $this->authorize('admin.tickets.multi-delete');
        
        $this->tickets->multiDelete($request->ids);
        
        event(new TicketsDeleted($this->tickets));

        return $this->successResponse(__('Selected ticket(s) deleted successfully.'));
    }

    /**
     * Restore the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function restore(Request $request)
    {
        $this->authorize('admin.tickets.restore');

        $tickets = $this->tickets->withTrashed()->where('id', $request->id);

        $tickets->restore();
        
        event(new TicketsRestored($tickets->first()));

        return $this->successResponse(__('Selected ticket(s) restored successfully.'));
    }

    /**
     * Force delete the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function forceDelete(Request $request)
    {
        $this->authorize('admin.tickets.force-delete');

        $tickets = $this->tickets->withTrashed()->where('id', $request->id);

        $first = $tickets->first();

        $tickets->forceDelete();
        
        event(new TicketsForceDeleted($first));

        return $this->successResponse(__('Selected ticket(s) force deleted successfully.'));
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
        $this->authorize('admin.tickets.remove-media');

        $media = Media::where(
            'uuid', 
            decrypt($request->uid)
        )->first();

        $media->delete();

        return $this->successResponse(__('Media deleted successfully.'));
    }

    /**
     * Store a newly created resource in storage.
     * @param AdminReplyTicketRequest $request
     * @return JsonResponse
     */
    public function reply(AdminReplyTicketRequest $request)
    {
        ini_set('memory_limit', '256M');

        $data = $request->only(
            'ticket_id', 
            'message'
        );

        if($request->has('is_note')) {
            $data['is_note'] = request('is_note');
        }

        $data['ticket_id'] = decrypt($data['ticket_id']);

        $model = $this->ticketConversations->create($data);

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $entry) {
                if ($entry->isValid()) {
                    $model->addMedia($entry)->toMediaCollection('attachments');
                }
            }

        }

        ini_set('memory_limit', '64M');

        event(new TicketsReplied($model));

        return $this->handleAjaxRedirectResponse(
            __('Ticket replied successfully.'), 
            route('admin.tickets.show', $model->ticket->number)
        );
    }

    public function statusChange(Request $request) 
    {
        // check if status submitted is not existing
        if(is_null(TicketStatus::name($request->status))) {
            return $this->errorResponse(__('Status not found!'));
        }

        $ticketId = decrypt($request->route('id'));

        $model = $this->tickets->findOrFail($ticketId);

        $this->tickets->update($model, [
            'status' => $request->status
        ]);

        $model->fresh();

        event(new TicketsStatusChanged($model));

        return $this->successResponse(__('Ticket status updated successfully.'));
    }

    public function updateConvo(AdminUpdateTicketConvoRequest $request, $ticketId, $convoId) 
    {
        $ticketId = decrypt($ticketId);
        $convoId = decrypt($convoId);

        $ticket = $this->tickets->updateConversationById(
            $ticketId, 
            $convoId,
            $request->message
        );

        return $this->handleAjaxRedirectResponse(
            __('Conversation updated successfully.'), 
            route('admin.tickets.show', $ticket->number)
        );
    }

    public function deleteConvo(Request $request, $ticketId, $convoId) 
    {
        $ticketId = decrypt($ticketId);
        $convoId = decrypt($convoId);

        $ticket = $this->tickets->deleteConversationById(
            $ticketId, 
            $convoId
        );

        return $this->handleAjaxRedirectResponse(
            __('Conversation deleted successfully.'), 
            route('admin.tickets.show', $ticket->number)
        );
    }

    public function updateSubject(AdminUpdateTicketSubjectRequest $request, $id) 
    {
        $id = decrypt($id);

        $model = $this->tickets->findOrFail($id);

        $this->tickets
            ->update($model, 
                $request->only('subject'));

        event(new TicketsSubjectUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Ticket subject updated successfully.'), 
            route('admin.tickets.show', $model->number)
        );
    }
}
