<?php

namespace Modules\Base\Support\Route;

use Modules\Base\Support\CrudTypes;

class RouteNameParser 
{	
	/**
     * Handle parseing display name
     * 
     * @param string $string
     * 
     * @return string
     */
	public function parse($string) 
	{
		return $this->handleUCFirstString($this->handleDotString($string));
	}

    /**
     * Handle parsing with dot string
     * 
     * @param string $string
     * 
     * @return array
     */
    protected function handleDotString($string) 
    {
        $array = explode('.', $string);

        // check if first value is admin of array
        if(in_array(reset($array), CrudTypes::lists(['api']))) {
            array_shift($array);

            return $array;
        }

        return $array;
    }   

    /**
     * Handle parsing with dash string
     * 
     * @param string $string
     * 
     * @return string
     */
    protected function handleDashString($string) 
    {
        return is_array(explode('-', $string)) 
            ? implode(' ', explode('-', $string))
            : $string;
    }

    /**
     * Handlinge converting capital first letter of the senter
     * 
     * @param array $permission
     * 
     * @return string
     */
    protected function handleUCFirstString($permission) 
    {
        return ucwords(
            $this->handleDashString(
                array_pop($permission)
            ) 
            . ' ' . 
            $this->handleDashString(
                array_shift($permission)
            )
            . ' ' .
            $this->handleDashString(
                implode(' ', $permission)
            )
        );
    }
}