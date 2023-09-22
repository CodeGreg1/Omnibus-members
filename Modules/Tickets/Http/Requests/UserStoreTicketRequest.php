<?php

namespace Modules\Tickets\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreTicketRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [   
            'user_id' => 'nullable',            
            'category_id' => 'nullable',            
            'subject' => 'required',            
            'priority' => 'required',            
            'message' => 'required',            
            'attachments' => 'nullable',
            'attachments.*' => 'max:2048'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('user.tickets.store');
    }
}
