<div class="form-inline w-100 mb-0">
    <div class="form-group w-100">
        <label class="mr-4">@lang('Set several hours before the ticket will auto closed after no reply from the customer.')</label>
        <input type="number" class="form-control ml-auto text-right" name="ticket_hours_before_auto_closed" id="ticket_hours_before_auto_closed" value="{{ setting('ticket_hours_before_auto_closed', 72) }}">
    </div>
</div>