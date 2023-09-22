<?php

namespace Modules\Base\Repositories;

use Torann\LaravelRepository\Repositories\AbstractRepository;

abstract class BaseRepository extends AbstractRepository
{	
	/**
     * Flush query cache
     * 
     * @return \Illuminate\Database\Eloquent\Model|static
     */
	public function flushQueryCache() 
    {
        $this->newQuery();

        $this->query->flushQueryCache();
    }

    /**
     * Retrieve the "count" result of the query.
     *
     * @param  string  $columns
     * 
     * @return int
     */
    public function count($columns = '*') 
    {
        return (new $this->model)->count();
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * 
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function firstOrCreate(array $attributes = [], array $values = []) 
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
    public function updateOrCreate(array $attributes, array $values = []) 
    {
        return (new $this->model)->updateOrCreate($attributes, $values);
    }

    /**
     * Delete multiple records
     * 
     * @param array $ids
     * 
     * @return boolean|null
     */
    public function multiDelete($ids) 
    {
        $model = (new $this->model)->whereIn('id', $ids)->delete();

        $this->flushQueryCache();

        return $model;
    }

    /**
     * Query only trashed record
     * 
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function onlyTrashed() 
    {
        $this->newQuery();

        return $this->query->onlyTrashed();
    }

    /**
     * Query with trashed record
     * 
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function withTrashed() 
    {
        $this->newQuery();

        return $this->query->withTrashed();
    }


    /**
     * Restore trashed record
     * 
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function restore() 
    {
        $this->newQuery();

        return $this->query->restore();
    }

    /**
     * Set the relationships that should be eager loaded.
     *
     * @param  string|array  $relations
     * @param  string|\Closure|null  $callback
     * @return $this
     */
    public function with($relations, $callback = null)
    {   
        $this->newQuery();

        if(is_null($callback)) {
            return (new $this->model)->with($relations);
        }
        
        return (new $this->model)->with($relations, $callback);
    }

    /**
     * Query with where null
     *
     * @param  string|array  $columns
     * @param  string  $boolean
     * @param  bool  $not
     * @return $this
     */
    public function whereNull($columns, $boolean = 'and', $not = false) 
    {
        $this->newQuery();

        return $this->query->whereNull($columns, $boolean = 'and', $not = false);
    }

    /**
     * Add a "where not null" clause to the query.
     *
     * @param  string|array  $columns
     * @param  string  $boolean
     * @return $this
     */
    public function whereNotNull($columns, $boolean = 'and')
    {
        $this->newQuery();
        
        return $this->query->whereNull($columns, $boolean, true);
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param  \Closure|string|array  $column
     * @param  mixed  $operator
     * @param  mixed  $value
     * @param  string  $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and') 
    {
        $this->newQuery();

        return $this->query->where($column, $operator, $value, $boolean);
    }

    /**
     * Add a "where in" clause to the query.
     *
     * @param  string  $column
     * @param  mixed  $values
     * @param  string  $boolean
     * @param  bool  $not
     * @return $this
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        $this->newQuery();

        return $this->query->whereIn($column, $values, $boolean = 'and', $not = false);
    }
}