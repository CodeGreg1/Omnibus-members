<?php

namespace Modules\Languages\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                        
            'title' => 'required',            
            'code' => 'required|max:2|unique:languages,code',            
            'direction' => 'required',            
            'flag_id' => 'required',            
            'active' => 'nullable',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.languages.store');
    }
}
