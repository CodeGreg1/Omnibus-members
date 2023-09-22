<?php

namespace Modules\Tickets\Http\Controllers\Web\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
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
use Modules\Tickets\Events\TicketsStatusUpdated;
use Modules\Tickets\Events\TicketsSubjectUpdated;
use Modules\Tickets\Repositories\TicketsRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\Categories\Repositories\CategoriesRepository;
use Modules\Tickets\Http\Requests\UserReplyTicketRequest;
use Modules\Tickets\Http\Requests\UserStoreTicketRequest;
use Modules\Tickets\Http\Requests\AdminReplyTicketRequest;
use Modules\Tickets\Http\Requests\AdminStoreTicketRequest;
use Modules\Tickets\Http\Requests\UserUpdateTicketRequest;
use Modules\Tickets\Http\Requests\AdminUpdateTicketRequest;
use Modules\Tickets\Http\Requests\UserUpdateTicketConvoRequest;
use Modules\Tickets\Repositories\TicketConversationsRepository;
use Modules\Tickets\Http\Requests\UserUpdateTicketSubjectRequest;

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
    protected $redirectTo = '/user/tickets';

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
        $this->authorize('user.tickets.index');

        return view('tickets::user.index', [
            'pageTitle' => __('Tickets'),
            'policies' => JsPolicy::get('tickets'),
            'statusCounter' => ticket_counter()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('user.tickets.create');
        
        return view('tickets::user.create', [
            'pageTitle' => __('Create new ticket'),
            'categories' => $this->categories->whereType(CategoryType::TYPE_SUPPORT)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param UserStoreTicketRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreTicketRequest $request)
    {
        ini_set('memory_limit', '256M');

        $model = $this->tickets->create($request->only(
            'number',
            'category_id', 
            'subject', 
            'priority', 
            'message', 
            'status'
        ));

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $entry) {
                if ($entry->isValid()) {
                    $model->addMedia($entry)->toMediaCollection('attachments');
                }
            }

        }

        ini_set('memory_limit', '64M');

        event(new TicketsCreated($model));

        return $this->handleAjaxRedirectResponse(
            __('Ticket created successfully.'), 
            $this->redirectTo
        );
    }

    /**
     * Show the specified resource.
     * @param int $number
     * @return Renderable
     */
    public function show($number)
    {
        $this->authorize('user.tickets.show');

        return view('tickets::user.show', [
            'pageTitle' => __('Show ticket'),
            'statuses' => TicketStatus::lists(),
            'tickets' => $this->tickets->where('number', $number)->with([
                'user', 
                'conversations' => function($query) {
                    $query->whereNull('is_note')
                        ->orderBy('created_at', 'desc');
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
        $this->authorize('user.tickets.edit');

        $users = User::pluck('first_name', 'id');
        
        return view('tickets::user.edit', [
            'pageTitle' => __('Edit ticket'),
            'tickets' => $this->tickets->findOrFail($id),
            'users' => $users,
            'categories' => $this->categories->whereType(CategoryType::TYPE_BLOG)
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UserUpdateTicketRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UserUpdateTicketRequest $request, $id)
    {
        $id = decrypt($id);
        
        $model = $this->tickets->findOrFail($id);

        $this->tickets
            ->update($model, 
                $request->only('message'));

        event(new TicketsUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Ticket updated successfully.'), 
            route('user.tickets.show', $model->number)
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->authorize('user.tickets.delete');

        $model = $this->tickets->findOrFail($request->id);

        $this->tickets->delete($model);
        
        event(new TicketsDeleted($model));
        
        return $this->successResponse(__('Ticket deleted successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function multiDestroy(Request $request)
    {
        $this->authorize('user.tickets.multi-delete');
        
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
        $this->authorize('user.tickets.restore');

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
        $this->authorize('user.tickets.force-delete');

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
        $this->authorize('user.tickets.remove-media');

        $media = Media::where(
            'uuid', 
            decrypt($request->uid)
        )->first();

        $media->delete();

        return $this->successResponse(__('Media deleted successfully.'));
    }

    /**
     * Store a newly created resource in storage.
     * @param UserReplyTicketRequest $request
     * @return JsonResponse
     */
    public function reply(UserReplyTicketRequest $request)
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

        $ticket = $this->tickets->findOrFail($data['ticket_id']);

        $model = $this->ticketConversations->create($data);

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $entry) {
                if ($entry->isValid()) {
                    $model->addMedia($entry)->toMediaCollection('attachments');
                }
            }

        }

        // change to open if status not closed
        if($ticket->status != TicketStatus::OPEN) {
            $ticket->status = TicketStatus::OPEN;
            $ticket->save();
        }

        ini_set('memory_limit', '64M');

        event(new TicketsReplied($model));

        return $this->handleAjaxRedirectResponse(
            __('Ticket replied successfully.'), 
            route('user.tickets.show', $model->ticket->number)
        );
    }

    public function updateConvo(UserUpdateTicketConvoRequest $request, $ticketId, $convoId) 
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
            route('user.tickets.show', $ticket->number)
        );
    }

    public function deleteConvo(Request $request, $ticketId, $convoId) 
    {
        $this->authorize('user.tickets.delete-convo');

        $ticketId = decrypt($ticketId);
        $convoId = decrypt($convoId);

        $ticket = $this->tickets->deleteConversationById(
            $ticketId, 
            $convoId
        );

        return $this->handleAjaxRedirectResponse(
            __('Conversation updated successfully.'), 
            route('user.tickets.show', $ticket->number)
        );
    }

    public function updateSubject(UserUpdateTicketSubjectRequest $request, $id) 
    {
        $id = decrypt($id);

        $model = $this->tickets->findOrFail($id);

        // check if the ticket is associated with the current user
        if($model->user_id != auth()->id()) {
            return $this->errorResponse(__('Resource Not Found.'));
        }

        $this->tickets
            ->update($model, 
                $request->only('subject'));

        event(new TicketsSubjectUpdated($model));

        return $this->handleAjaxRedirectResponse(
            __('Ticket subject updated successfully.'), 
            route('user.tickets.show', $model->number)
        );
    }
}
