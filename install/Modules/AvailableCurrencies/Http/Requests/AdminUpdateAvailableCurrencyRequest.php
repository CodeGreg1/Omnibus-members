<?php

namespace Modules\AvailableCurrencies\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateAvailableCurrencyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        return [
                        
            'currency_id' => 'required',            
            'name' => 'required|max:100|unique:available_currencies,name,' . request()->route('id'),            
            'symbol' => 'required|max:10',            
            'code' => 'required|max:10',            
            'exchange_rate' => 'required',            
            'status' => 'nullable',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.available-currencies.update');
    }
}
