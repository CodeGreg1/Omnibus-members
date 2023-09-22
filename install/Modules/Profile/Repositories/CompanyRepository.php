<?php

namespace Modules\Profile\Repositories;

use Modules\Profile\Models\Company;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class CompanyRepository extends AbstractRepository
{
	/**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Company::class;

    /**
     * Get the company record by user id and with addresses
     * 
     * @param inteter $userId
     * 
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function getByUserIdWithAddress($userId) 
    {
        return Company::with('addresses')->where('user_id', $userId)->first();
    }

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