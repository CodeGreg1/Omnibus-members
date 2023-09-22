<?php

namespace Modules\Tickets\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Tickets\Repositories\TicketsRepository;

class ApiUpdateTicketRequest extends FormRequest
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
                        
            'number' => 'required|integer|min:-2147483648|max:2147483647',            
            'user_id' => 'nullable',            
            'category_id' => 'nullable',            
            'subject' => 'required',            
            'priority' => 'required',            
            'message' => 'required',            
            'attachments' => $attachmentsValidation,
            'attachments.*' => 'max:2048',            
            'status' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::any([
            'user.tickets.update', 
            'admin.tickets.update'
        ]);
    }
}
