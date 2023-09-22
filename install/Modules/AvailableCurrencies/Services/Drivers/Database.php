<?php

namespace Modules\AvailableCurrencies\Services\Drivers;

use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Database\DatabaseManager;

class Database extends AbstractDriver
{
    /**
     * Database manager instance.
     *
     * @var DatabaseManager
     */
    protected $model;

    /**
     * Create a new driver instance.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $model = $this->config('model');

        $this->model = new $model;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $params)
    {
        // Ensure the currency doesn't already exist
        if ($this->find($params['code'], null) !== null) {
            return 'exists';
        }

        // Created at stamp
        $created = new DateTime('now');

        $params = array_merge([
            'name' => '',
            'code' => '',
            'symbol' => '',
            'format' => '',
            'exchange_rate' => 1,
            'active' => 1,
        ], $params);

        return $this->model->create($params);
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        $collection = $this->model->with(['currency'])->get();

        return $collection->keyBy('code')
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => strtoupper($item->code),
                    'symbol' => $item->symbol,
                    'format' => $item->format,
                    'exchange_rate' => $item->exchange_rate,
                    'active' => !!$item->status,
                    'model' => $item->currency->toArray()
                ];
            })
            ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function find($code, $active = 1)
    {
        $query = $this->model
            ->with(['currency'])
            ->where('code', strtoupper($code));

        // Make active optional
        if (is_null($active) === false) {
            $query->where('active', $active);
        }

        return $query->first();
    }

    /**
     * {@inheritdoc}
     */
    public function update($code, array $attributes)
    {
        return $this->model
            ->where('code', strtoupper($code))
            ->update($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($code)
    {
        return $this->model
            ->where('code', strtoupper($code))
            ->delete();
    }
}