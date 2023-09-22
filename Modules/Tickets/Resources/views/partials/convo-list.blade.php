<ul class="conversation-list list-unstyled list-unstyled-border list-unstyled-noborder">
    @if(count($tickets->conversations))
        @foreach($tickets->conversations as $conversation)

        <li class="media {{ $conversation->is_note == 1 ? 'note-media' : '' }}">
            <img alt="image" class="mr-3 rounded-circle" width="70" src="{{ !is_null($conversation->user) ? $conversation->user->avatar : setting('default_profile_photo') }}">
            <div class="media-body">
                <div class="media-right convo-list-actions">
                    @if($tickets->user_id == auth()->id() && $conversation->user->id == auth()->id())
                    <i class="fas fa-edit text-muted btn-edit-convo" 
                        data-route="{{ route('user.tickets.update-convo', [encrypt($tickets->id), encrypt($conversation->id)]) }}"></i>&nbsp;&nbsp;&nbsp;
                    <i class="fas fa-trash-alt text-muted btn-delete-convo" 
                        data-route="{{ route('user.tickets.delete-convo', [encrypt($tickets->id), encrypt($conversation->id)]) }}"></i>
                    @endif

                    {{-- For Admin / Agent --}}
                    @if($tickets->user_id != auth()->id())
                    <i class="fas fa-edit text-muted btn-edit-convo" 
                        data-route="{{ route('admin.tickets.update-convo', [encrypt($tickets->id), encrypt($conversation->id)]) }}"></i>&nbsp;&nbsp;&nbsp;
                    <i class="fas fa-trash-alt text-muted btn-delete-convo" 
                        data-route="{{ route('admin.tickets.delete-convo', [encrypt($tickets->id), encrypt($conversation->id)]) }}"></i>
                    @endif
                </div>
                <div class="media-title mb-1">
                    @if($conversation->user->id == auth()->id())
                        You
                    @else
                        {{ $conversation->user->first_name }} {{ $conversation->user->last_name }} 
                    @endif
                    
                    @if($conversation->is_note == 1)
                        <span class="text-warning">@lang('added a note')</span>
                    @else
                        <span class="text-muted">@lang('replied')</span>
                    @endif
                </div>

                <span class="text-time" title="{{ $conversation->created_at_formatted }}">{{ $conversation->created_at_timeago }}</span>
                <div class="media-description">{!! $conversation->message !!}</div>

                @if(count($conversation->attachments))
                <div class="conversation-attachments-wrapper">
                    <h6 class="heading">@lang('Attached Files'):</h6>
                    <ul class="conversation-attachments">
                        @foreach($conversation->attachments as $attachment)
                            <li>
                                <span class="text-muted">@if(in_array('.' . $attachment->extension, explode(',', MediaImageType::$lists))) <i class="fas fa-image"></i> @else <i class="fas fa-paperclip"></i> @endif </span>
                                <a href="{{ $attachment->original_url }}" target="_blank">{{ $attachment->file_name }}</a>
                                
                                @if(auth()->id() == $tickets->user_id && $conversation->user->id == auth()->id())
                                <span class="ticket-remove-attachement" data-uid="{{ encrypt($attachment->uuid) }}" data-route="{{ route($removeMediaRoute) }}"><i class="fas fa-times"></i></span>
                                @endif

                                {{-- For Admin / Agent --}}
                                @if($tickets->user_id != auth()->id())
                                <span class="ticket-remove-attachement" data-uid="{{ encrypt($attachment->uuid) }}" data-route="{{ route($removeMediaRoute) }}"><i class="fas fa-times"></i></span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </li>
        @endforeach
    @endif

    <li class="media">
        <img alt="image" class="mr-3 rounded-circle" width="70" src="{{ !is_null($tickets->user) ? $tickets->user->avatar : setting('default_profile_photo') }}">
        <div class="media-body">
            <div class="media-right convo-list-actions">
                @if($tickets->user_id == auth()->id())
                    <i class="fas fa-edit text-muted btn-edit-convo" 
                        data-route="{{ route('user.tickets.update', [encrypt($tickets->id)]) }}"></i>
                @else
                    <i class="fas fa-edit text-muted btn-edit-convo" 
                        data-route="{{ route('admin.tickets.update', [encrypt($tickets->id)]) }}"></i>
                @endif
            </div>
            <div class="media-title mb-1">{{ ticket_conversation_name($tickets) }} <span class="text-muted">@lang('started the conversation')</span></div>
            <span class="text-time" title="{{ $tickets->created_at_formatted }}">{{ $tickets->created_at_timeago }}</span>
            <div class="media-description text-muted">{!! $tickets->message !!}</div>

            @if(count($tickets->attachments))
            <div class="conversation-attachments-wrapper">
                <h6 class="heading">@lang('Attached Files'):</h6>
                <ul class="conversation-attachments">
                    @foreach($tickets->attachments as $attachment)
                        <li>
                            <span class="text-muted">@if(in_array('.' . $attachment->extension, explode(',', MediaImageType::$lists))) <i class="fas fa-image"></i> @else <i class="fas fa-paperclip"></i> @endif </span>
                            <a href="{{ $attachment->original_url }}" target="_blank">{{ $attachment->file_name }}</a>
                            <span class="ticket-remove-attachement" data-uid="{{ encrypt($attachment->uuid) }}" data-route="{{ route($removeMediaRoute) }}"><i class="fas fa-times"></i></span>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </li>
</ul>