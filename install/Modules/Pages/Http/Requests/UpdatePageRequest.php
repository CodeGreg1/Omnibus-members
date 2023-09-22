<?php

namespace Modules\Pages\Http\Requests;

use ReflectionClass;
use Illuminate\Support\Str;
use Modules\Pages\Models\Page;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Pages\Traits\SectionValidators;

class UpdatePageRequest extends FormRequest
{
    use SectionValidators;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|unique:pages,name,' . $this->id,
            'slug' => 'required|unique:pages,slug,' . $this->id
        ];

        if ($this->type === 'section') {
            $rules['sections'] = ['required', 'array'];
        }
        if ($this->type === 'wysiwyg') {
            $rules['content'] = ['required'];
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
        if ($this->type === 'section') {
            $this->merge([
                'sections' => json_decode($this->get('sections')),
            ]);
        }

        $count = 0;
        $pageTitle = $this->get('page_title') ?? $this->get('name');
        $pageDesc = $this->get('page_description') ?? $this->get('description');

        do {
            $slug = Str::slug(Str::substr(($this->get('slug') ?? $this->get('name')), 0, 70));
            if ($count) {
                $slug = $slug . '-' . $count;
            }
            $count = $count + 1;
        } while (Page::where('slug', $slug)->where('id', '!=', $this->id)->count());

        $this->merge([
            'slug' => $slug,
            'page_title' => Str::substr($pageTitle, 0, 70),
            'page_description' => Str::substr($pageDesc, 0, 320),
            'has_breadcrumb' => $this->get('has_breadcrumb') === 'yes' ? 1 : 0
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.pages.update');
    }
}
