<?php

namespace Modules\Tickets\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Tickets\Repositories\TicketsRepository;

class UserUpdateTicketRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $repo = (new TicketsRepository)->find($this->id);
        
        $attachmentsValidation = 'nullable';
        
        return [         
            'message' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('user.tickets.update');
    }
}
