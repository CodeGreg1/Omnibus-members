<?php

namespace Modules\Affiliates\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Affiliates\Models\AffiliateUser;

class UserStoreAffiliateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'unique:affiliate_users,code'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $type = setting('affiliate_code_value_type', 'username');

        $count = 0;
        do {
            $code = $this->user()->generateAffiliateCode($type);
            if ($type === 'username') {
                $code .= $count ? $count : '';
            }
            $count = $count + 1;
        } while (AffiliateUser::where('code', $code)->count());

        $approved = 0;
        $active = 0;
        if (setting('auto_approve_affiliate_membership') === 'enable') {
            $approved = 1;
            $active = 1;
        }

        $this->merge([
            'user_id' => $this->user()->id,
            'code' => $code,
            'approved' => $approved,
            'active' => $active,
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
        return Gate::allows('user.affiliates.store');
    }
}
