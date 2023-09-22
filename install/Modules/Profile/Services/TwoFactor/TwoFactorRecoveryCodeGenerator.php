<?php

namespace Modules\Profile\Services\TwoFactor;

use Illuminate\Support\Str;

trait TwoFactorRecoveryCodeGenerator
{	
	/**
	 * @var $totalCodes
	 */
	protected $totalCodes = 8;

	/**
	 * @var $chars
	 */
	protected $chars = 10;

	/**
	 * @var $blocks
	 */
	protected $blocks = 2;

	/**
	 * @var $separator
	 */
	protected $separator = "-";

	/**
	 * Handle on generating two factor recovery codes
	 * 
	 * @return string
	 */
	protected function twoFactorRecoveryCodeGenerator() 
    {
    	$counts = $this->totalCodes;

    	$result = [];

    	for($x=1; $x<=$counts; $x++) {
    		$result[$x] = $this->string(); 
    	}

    	return $result;
    }

    /**
     * Generate recovery code
     * 
     * @return string
     */
	public function string() 
	{
		$result = [];

    	$cntr = 1;
    	for($x=1; $x<=$this->blocks; $x++) {
    		if($x == 1) {
    			$result[] = is_float($this->calculateBlock()) 
    				? $this->strRandom(ceil($this->calculateBlock())) 
    				: $this->strRandom(floor($this->calculateBlock()));
    		} else {
    			$result[] = $this->strRandom(floor($this->calculateBlock()));
    		}
    	}

    	return implode($this->separator, $result);
	}

	/**
	 * Handle on calculating block
	 * 
	 * @return string
	 */
	protected function calculateBlock() {
    	return $this->chars/$this->blocks;
    }

    /**
	 * Handle on generating random string
	 * 
	 * @return string
	 */
    protected function strRandom($size) 
    {
    	return strtoupper(Str::random($size));
    }
}