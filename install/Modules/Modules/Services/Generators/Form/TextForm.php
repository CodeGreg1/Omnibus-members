<?php

namespace Modules\Modules\Services\Generators\Form;

use Modules\Modules\Services\Generators\Form\FormInterface;

class TextForm extends Form implements FormInterface
{
	/**
	 * Generate text form
	 * 
	 * @param string $type
	 * @param array $attributes
	 * @param array $moduleReplacements
	 * 
	 * @return string
	 */
	public function generate($type, $attributes, $moduleReplacements) 
	{
		return $this->generateField($type, $attributes, $moduleReplacements);
	}
}