<?php

namespace Modules\Blogs\Http\Requests;

use Illuminate\Support\Str;
use Modules\Blogs\Models\Blog;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|unique:blogs,title|max:255',
            'description' => 'required|max:65535',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'media_id' => 'required|exists:media,id',
            'tags' => 'required|array'
        ];
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
            'modified_at' => now()
        ]);

        $count = 0;

        $pageTitle = $this->get('page_title') ?? $this->get('title');
        $pageDesc = $this->get('page_description') ?? $this->get('description');

        do {
            $slug = Str::slug(Str::substr(($this->get('slug') ?? $this->get('title')), 0, 70));
            if ($count) {
                $slug = $slug . '-' . $count;
            }
            $count = $count + 1;
        } while (Blog::where('slug', $slug)->count());

        $this->merge([
            'slug' => $slug,
            'page_title' => Str::substr($pageTitle, 0, 70),
            'page_description' => Str::substr($pageDesc, 0, 320),
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'category_id.required' => __('The category field is required.'),
            'category_id.exists' => __('Selected category does not exists.'),
            'media_id.required' => __('Thumbnail is required.'),
            'media_id.exists' => __('Thumbnail does not exists.'),
            'tags.required' => __('Please add atleast 1 tag.'),
            'page_title.required' => __('The title field is required.'),
            'page_title.max' => __('The title field must not exceed 70 characters including spaces.'),
            'page_description.required' => __('The description field is required.'),
            'page_description.max' => __('The description field must not exceed 320 characters including spaces.'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.blogs.store');
    }
}
