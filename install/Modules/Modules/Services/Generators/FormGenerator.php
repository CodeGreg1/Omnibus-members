<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Modules\Modules\Services\Generators\Form\FormInterface;

class FormGenerator
{
	/**
	 * Handle generating form
	 * 
	 * @param string $type
	 * @param array $attributes
	 * @param FormInterface $form
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	public function generate(string $type, array $attributes, FormInterface $form, array $moduleReplacements) 
	{
		return $form->generate($type, $attributes, $moduleReplacements);
	}
}