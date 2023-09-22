<?php

namespace Modules\Modules\Services\Generators;

class ChoicesModelGenerator {

	/**
	 * @var string $fieldName
	 */
	protected $fieldName;

	/**
	 * @var string $type
	 */
	protected $type;

	/**
	 * @var array $choices
	 */
	protected $choices;

	/**
	 * @param string $fieldName
	 * @param string $type
	 * @param array $choices
	 */
	public function __construct($fieldName, $type, array $choices) 
	{
		$this->type = $type;
		$this->choices = $choices;
		$this->fieldName = $fieldName;
	}

	/**
	 * Handle generating model property choices
	 * 
	 * @return string
	 */
	public function generate() 
	{
		return "

	/**
     * @var array ".strtoupper($this->fieldName)."_".strtoupper($this->type)."
     */
    public const ".strtoupper($this->fieldName)."_".strtoupper($this->type)." = [
".$this->options()."
    ];";
	}

	/**
	 * Handle generating options
	 * 
	 * @return string
	 */
	protected function options() 
	{
		$content = '';
		$cntr = 0;
		foreach($this->choices as $choice) {

			if($cntr == 0) {
				$content .= $this->firstTemplate($choice['database_value'], $choice['label_text']);
			} else {
				$content .= $this->nextTemplate($choice['database_value'], $choice['label_text']);
			}

			$cntr++;
		}

		// check if last has , character
        if(substr($content, -1) == ',') {
            return substr($content, 0, -1);
        }

		return $content;
	}

	/**
	 * Handle generating first element template
	 * 
	 * @param string $value
	 * @param string $text
	 * 
	 * @return string
	 */
	protected function firstTemplate($value, $text) 
	{
		return "    	'$value' => '$text',	";
	}

	/**
	 * Handle generating next after first template
	 * 
	 * @param string $value
	 * @param string $text
	 * 
	 * @return string
	 */
	protected function nextTemplate($value, $text) 
	{
		return "
        '$value' => '$text',";
	}

}