<?php

namespace Modules\Tickets\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Tickets\Models\Ticket;
use Modules\Tickets\Support\TicketStatus;
use Modules\Base\Repositories\BaseRepository;
use Illuminate\Contracts\Database\Eloquent\Builder;

class TicketsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Ticket::class;

    /**
     * Set to open ticket by ID
     * 
     * @param int $id
     * 
     * @return Ticket
     */
    public function setToOpen($id) 
    {
        return $this->getModel()
            ->where('id', $id)
            ->update([
                'status' => TicketStatus::OPEN
            ]);
    }

    /**
     * Set to onhold ticket by ID
     * 
     * @param int $id
     * 
     * @return Ticket
     */
    public function setToOnhold($id) 
    {
        return $this->getModel()
            ->where('id', $id)
            ->update([
                'status' => TicketStatus::ONHOLD
            ]);
    }

    /**
     * Set to closed ticket by ID
     * 
     * @param int $id
     * 
     * @return Ticket
     */
    public function setToClosed($id) 
    {
        return $this->getModel()
            ->where('id', $id)
            ->update([
                'status' => TicketStatus::CLOSED
            ]);
    }

    /**
     * Get open ticket latest conversation
     * 
     * @return Ticket
     */
    public function getLatestConversation() 
    {
        return $this->getModel()
            ->with([
                'conversations' => function($query) {
                    $query->whereNotNull('user_id')
                        ->orderBy('id', 'desc')
                        ->first();
                }
            ])
            ->whereHas('conversations')
            ->whereNotNull('user_id')
            ->where('status', TicketStatus::OPEN)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Count all
     * 
     * @param int|null $userId
     * 
     * @return int
     */
    public function countAll($userId = null) 
    {
        $model = $this->getModel();

        if(is_null($userId)) {
            $model = $model->whereNotNull('user_id');
        } else {
            $model = $model->where('user_id', $userId);
        }

        return $model->count();
    }

    /**
     * Count all trashed
     * 
     * @param int|null $userId
     * 
     * @return int
     */
    public function countTrashed($userId = null) 
    {
        $model = $this->getModel()->onlyTrashed();

        if(!is_null($userId)) {
            $model = $model->where('user_id', $userId);
        }

        return $model->count();
    }

    /**
     * Count all by statuses
     * 
     * @param int|null $userId
     * 
     * @return Ticket
     */
    public function countStatuses($userId = null) 
    {
        $model = $this->getModel()
            ->select(DB::raw('count(status) as total_status'), 'status')
            ->groupBy('status');

        if(is_null($userId)) {
            $model = $model->whereNotNull('user_id');
        } else {
            $model = $model->where('user_id', $userId);
        }

        return $model->get();
    }

    /**
     * Update ticket conversation with restrictions
     * 
     * @param int $ticketId
     * @param int $convoId
     * @param string $message
     * 
     * @return Ticket|\Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateConversationById($ticketId, $convoId, $message) 
    {
        $model = $this->getModel()->findOrFail($ticketId);

        $ticket = $this->getModel()->where('id', $ticketId)->with('conversations', function($query) use($convoId) {
            $query->where('id', $convoId);
        })->whereHas('conversations', function (Builder $query) use($model, $convoId){
            $q = $query->where('id', $convoId);

            if($model->user_id == auth()->id()) {
                $q->where('user_id', auth()->id());
            }
        })->firstOrFail();

        $convo = $ticket->conversations->first();

        $convo->message = $message;
        $convo->save();

        return $ticket;
    }

    /**
     * Delete ticket conversation with restrictions
     * 
     * @param int $ticketId
     * @param int $convoId
     * 
     * @return Ticket|\Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deleteConversationById($ticketId, $convoId) 
    {
        $model = $this->getModel()->findOrFail($ticketId);

        $ticket = $this->getModel()->where('id', $ticketId)->with('conversations', function($query) use($convoId) {
            $query->where('id', $convoId);
        })->whereHas('conversations', function (Builder $query) use($model, $convoId) {
            $q = $query->where('id', $convoId);

            if($model->user_id == auth()->id()) {
                $q->where('user_id', auth()->id());
            }
        })->firstOrFail();

        $convo = $ticket->conversations->first();
        $convo->delete();

        return $ticket;
    }
}