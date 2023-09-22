<?php

namespace Modules\Pages\Http\Requests;

use Illuminate\Support\Str;
use Modules\Pages\Models\Page;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class DuplicatePageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $count = 0;
        do {
            $slug = Str::slug(Str::substr($this->get('name'), 0, 70));
            if ($count) {
                $slug = $slug . '-' . $count;
            }
            $count = $count + 1;
        } while (Page::where('slug', $slug)->count());

        $countName = 0;
        do {
            $name = $this->get('name');
            if ($countName) {
                $name = $name . '-' . $countName;
            }
            $countName = $countName + 1;
        } while (Page::where('name', $name)->count());

        $this->merge([
            'slug' => $slug,
            'name' => $name,
            'description' => $this->get('description') ?? $name
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.pages.duplicate');
    }
}
