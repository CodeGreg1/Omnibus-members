<?php

namespace Modules\Photos\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UploadPhotoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'folder' => 'required|exists:photos,id',
            'photos' => 'required|max:10',
            'photos.*' => 'image'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        if ($this->has('photos')) {
            $validator->after(
                function ($validator) {
                    $totalSize = 0;
                    foreach ($this->photos as $key => $file) {
                        $num = $key + 1;
                        $size = $file->getSize();
                        $totalSize = $totalSize + $size;
                        if ($size > 2000000) {
                            $validator->errors()->add(
                                "photos.{$num}",
                                __('The Photo#:key must not be greater than 2MB.', [
                                    'key' => $num
                                ])
                            );
                        }
                    }

                    if ($totalSize > 2000000) {
                        $validator->errors()->add(
                            "photos",
                            __('Total photo sizes exceeds 2MB.')
                        );
                    }
                }
            );
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [
            'photos.max' => __('Upload must not greater than 10 photos.')
        ];

        if ($this->has('photos')) {
            foreach ($this->photos as $key => $value) {
                $messages['photos.' . $key] = __('The Photo#:key must not be greater than 2MB.', [
                    'key' => $key + 1
                ]);
            }
        }

        return $messages;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.photos.upload');
    }
}
