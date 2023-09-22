<?php

namespace Modules\Base\Presenters\Traits;

use Exception;
use Modules\Base\Presenters\Presenter;

trait Presentable
{
    /**
     * @var Presenter $instance
     */
    protected $instance;

    /**
     * Handle on presenting user
     * 
     * @return mixed
     * 
     * @throws Exception
     */
    public function present()
    {
        if($this->isInstanceObject()){
            return $this->instance;
        }

        if($this->isInstancePropertyAndClassExists()){
            return $this->instance = new $this->presenter($this);
        }

        throw new Exception(__(
            'Property :presenter is not defined in :class', [
                'presenter' => $presenter, 
                'class' => get_class($this)
            ]
        ));
    }

    /**
     * Handle on checking if instance is object
     * 
     * @return boolean
     */
    protected function isInstanceObject() 
    {
        return is_object($this->instance);
    }

    /**
     * Handle on checking instance is propery and class exists
     * 
     * @return boolean
     */
    protected function isInstancePropertyAndClassExists() 
    {
        return property_exists($this, 'presenter') && class_exists($this->presenter);
    }
}