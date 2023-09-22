<?php

namespace Modules\Base\Traits;

trait UnknownName
{
	/**
     * @var string $unknownName
     */
    protected $unknownName = 'Unknown';

    /**
     * Get unknown name
     * 
     * @return string
     */
	public function getUnknownName() 
    {
        return $this->unknownName;
    }
}