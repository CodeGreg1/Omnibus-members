<?php

namespace Modules\Carts\Services;

use Illuminate\Support\Collection;

/**
 * Class Session.
 *
 * the owner of the cart (User).
 */
class Session extends Collection
{
    /**
     * the session constructor
     *
     * @param array $items
     */
    public function __construct($items)
    {
        parent::__construct($items);
    }

    /**
     * allows access to items of collection and the model attributes
     *
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->has($name) || $name == 'model') {
            return !is_null($this->get($name)) ? $this->get($name) : $this->getAssociatedModel();
        }
        return null;
    }

    /**
     * return the unique cart key of the cart by session.
     *
     * @return string|int|mixed
     */
    public function getCartItemsKey()
    {
        if ($this->model) {
            return $this->id . '::cart_items';
        }

        return $this->id . '_cart_items';
    }

    /**
     * return the cart's conditions key.
     *
     * @return string|int|mixed
     */
    public function getCartConditionsKey()
    {
        if ($this->model) {
            return $this->id . '::cart_conditions';
        }

        return $this->id . '_cart_conditions';
    }

    /**
     * return the associated model of an item
     *
     * @return bool
     */
    protected function getAssociatedModel()
    {
        if (!$this->has('associatedModel') || !$this->get('associatedModel')) {
            return null;
        }

        $associatedModel = $this->get('associatedModel');

        return with(new $associatedModel())->find($this->get('id'));
    }
}