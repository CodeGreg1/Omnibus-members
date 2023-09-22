<?php

namespace Modules\Modules\Services\Generators\Form;

interface FormInterface {
	public function generate($type, $attributes, $moduleReplacements);
}