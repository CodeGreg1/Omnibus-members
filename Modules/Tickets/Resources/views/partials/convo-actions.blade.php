<div class="convo-actions-wrapper">
    <button class="btn btn-white" id="btn-add-a-reply"><i class="fas fa-comment text-primary"></i> Add Reply</button>

    @if($tickets->user_id != auth()->id())
    <button class="btn btn-white" id="btn-add-a-note"><i class="fas fa-sticky-note text-warning"></i> Add Note</button>
    @endif

    @if($tickets->user_id == auth()->id())
    <div class="float-right mt-1">
        <span class="badge badge-{{\Modules\Tickets\Support\TicketStatus::color($tickets->status)}}">{{ \Modules\Tickets\Support\TicketStatus::name($tickets->status) }}</span>
    </div>
    @endif
</div>