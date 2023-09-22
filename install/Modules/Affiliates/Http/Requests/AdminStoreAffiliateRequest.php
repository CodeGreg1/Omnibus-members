<?php

namespace Modules\Affiliates\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class AdminStoreAffiliateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
            'code' => $this->user()->username,
            'approved' => 0,
            'active' => 0,
            'allow_registration_commission' => 1,
            'allow_deposit_commission' => 1,
            'allow_subscription_commission' => 1
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
        $validator->after(
            function ($validator) {
                if ($this->user()->affiliate) {
                    $validator->errors()->add('user', __('User is already a member or has pending request.'));
                }
            }
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.affiliates.store');
    }
}
