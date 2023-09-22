<?php

namespace Modules\Affiliates\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommissionTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'levels' => ['required', 'array', 'min:1', 'max:10'],
            'levels.*' => ['required', 'numeric', 'min:0.01', 'max:99']
        ];

        if ($this->has('status')) {
            $rules['status'] = ['integer'];
        }

        $this->messages = [];
        foreach ($this->get('levels') as $key => $level) {
            $this->messages["levels.$key.required"] = __('Level :level rate is required.', [
                'level' => $key + 1
            ]);
            $this->messages["levels.$key.numeric"] = __('Level :level rate must me a number.', [
                'level' => $key + 1
            ]);
            $this->messages["levels.$key.min"] = __('Level :level rate must me a number greater than 0.01.', [
                'level' => $key + 1
            ]);
            $this->messages["levels.$key.max"] = __('Level :level rate must me a number less than 99.', [
                'level' => $key + 1
            ]);
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'levels' => json_decode($this->request->get('levels'))
        ]);
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->merge([
                'levels' => json_encode($this->get('levels')),
                'active' => $this->get('status') ?? 0
            ]);
        });
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return array_merge(
            $this->messages,
            [
                'active.integer' => __('The status must be an integer.')
            ]
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.affiliates.commission-types.update');
    }
}
