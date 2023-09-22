<?php

namespace Modules\Sitemap\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSitemapSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('sitemap_auto_rebuild')) {
            $sitemap_auto_rebuild = 1;
        } else {
            $sitemap_auto_rebuild = 0;
        }

        $this->merge([
            'sitemap_auto_rebuild' => $sitemap_auto_rebuild
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return Gate::allows('admin.sitemap.update');
        return true;
    }
}
