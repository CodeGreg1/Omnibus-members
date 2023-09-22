<?php

namespace Modules\Tickets\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class AdminStoreTicketRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'number' => 'required|integer|min:-2147483648|max:2147483647',           
            'user_id' => 'nullable',
            'category_id' => 'nullable',
            'subject' => 'required',
            'priority' => 'required',
            'message' => 'required',
            'attachments' => 'nullable',
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
        return Gate::allows('admin.tickets.store');
    }
}
