<?php

namespace Modules\Languages\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class AddLanguagePhraseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
            'key' => 'required'
        ], $this->getValueValidationKey());
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.modules.add-language-phrase');
    }

    /**
     * Handle dynamic validation for languages
     * 
     * @return array
     */
    protected function getValueValidationKey() 
    {
        $parent = 'value';

        $keys = array_keys(request($parent));

        foreach($keys as $key) {
            $result[$parent . '.' . $key] = 'required';
        }

        return $result;
    }
}
