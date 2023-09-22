<div class="card card-form">
    <div class="card-header">
        <h4 class="ticket-subject">Ticket Details</h4>
    </div>
    
    <div class="card-body p-20">
        <div class="form-group">
            <label for="message">@lang('Status')</label>
            
            <select class="form-control select2" name="status" id="ticket-change-status">
                @foreach($statuses as $key => $status)
                    <option value="{{ $key }}" {{ $tickets->status == $key ? 'selected' : '' }}>{{ $status['name'] }}</option>
                @endforeach
            </select>
        </div>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Ticket by</b>
                <span><a href="{{ route('admin.users.show', $tickets->user->id) }}">{{ $tickets->user->full_name }}</a></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Contact</b>
                <span><a href="mailto:{{ protected_data($tickets->user->email, 'email') }}">{{ protected_data($tickets->user->email, 'email') }}</a></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Priority</b>
                <span class="badge badge-{{ \Modules\Tickets\Support\TicketPriority::color($tickets->priority) }}">{{ \Modules\Tickets\Support\TicketPriority::name($tickets->priority) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Created</b>
                <span>{{ $tickets->created_at_formatted }}</span>
            </li>
        </ul>

        @can('admin.tickets.delete')
        <button class="btn btn-danger mt-4 btn-block" id="btn-admin-delete-ticket" data-id="{{ encrypt($tickets->id) }}">Delete this ticket</button>
        @endcan
    </div>
</div>