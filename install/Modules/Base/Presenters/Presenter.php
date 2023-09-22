<?php

namespace Modules\Base\Presenters;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

abstract class Presenter
{
    /**
     * @var Model $entity
     */
    protected $entity;

    /**
     * @param Model $entity
     */
    public function __construct(Model $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param string $property
     * 
     * @return bool
     */
    public function __isset($property)
    {
        return $this->isMethodExists(Str::camel($property));
    }

    /**
     * @param string $property
     * 
     * @return mixed
     */
    public function __get($property)
    {
        $camelProperty = Str::camel($property);

        if($this->isMethodExists($camelProperty)) {
            return $this->{$camelProperty}();
        }

        return $this->entity->{Str::snake($property)};
    }

    /**
     * Handle checking if method is exists
     * 
     * @param string $property
     * 
     * @return boolean
     */
    protected function isMethodExists($property) 
    {
        return method_exists($this, $property);
    }
}
