<?php

namespace Modules\Pages\Traits;

use Illuminate\Support\Str;
use Modules\Pages\Facades\PageBuilder;

trait SectionValidators
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('sections')) {
            $this->merge([
                'sections' => json_decode($this->get('sections')),
            ]);
        }

        $this->merge([
            'slug' => Str::slug($this->slug),
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
            if ($this->has('sections')) {
                foreach ($this->get('sections') as $section) {
                    $sectionBuilder = PageBuilder::getSection($section->template);
                    if ($sectionBuilder && count($sectionBuilder->validators)) {
                        foreach ($sectionBuilder->validators as $sectionValidator) {
                            if (class_exists($sectionValidator)) {
                                $validator = $sectionValidator::validate($validator, $section);
                            }
                        }
                    }
                }
            }
        });
    }
}
