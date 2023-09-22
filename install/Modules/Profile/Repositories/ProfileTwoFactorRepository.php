<?php

namespace Modules\Profile\Repositories;

use Modules\Profile\Models\ProfileTwoFactor;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class ProfileTwoFactorRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = ProfileTwoFactor::class;

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * 
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function firstOrCreate(array $attributes, array $values) 
    {
        return (new $this->model)->firstOrCreate($attributes, $values);
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function updateOrCreate(array $attributes, array $values) 
    {
        return (new $this->model)->updateOrCreate($attributes, $values);
    }
}