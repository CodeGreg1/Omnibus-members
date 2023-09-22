<?php

namespace Modules\Carts\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Modules\Carts\Exceptions\InvalidItemConditionException;
use Modules\Carts\Helpers\Helpers;

class ItemConditionCollection extends Collection
{
    /**
     * @var array
     */
    private $args;

    /**
     * @param array $args (name, type, target, value)
     * @throws InvalidConditionException
     */
    public function __construct(array $args)
    {
        $this->args = $args;

        if (Helpers::isMultiArray($args)) {
            throw new InvalidItemConditionException('Multi dimensional array is not supported.');
        } else {
            $this->validate($this->args);
        }
    }

    /**
     * the target of where the condition is applied.
     * NOTE: On conditions added to per item bases, target is not needed.
     *
     * @return mixed
     */
    public function getTarget()
    {
        return (isset($this->args['target'])) ? $this->args['target'] : '';
    }

    /**
     * the name of the condition
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->args['name'];
    }

    /**
     * the type of the condition
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->args['type'];
    }

    /**
     * the operation for the value
     *
     * @return string
     */
    public function getOperation()
    {
        if ($this->valueIsToBeSubtracted($this->getValue())) {
            return 'subtract';
        }
        return 'add';
    }

    /**
     * Set the order to apply this condition. If no argument order is applied we return 0 as
     * indicator that no assignment has been made
     * @param int $order
     * @return Integer
     */
    public function setOrder($order = 1)
    {
        $this->args['order'] = $order;
    }

    /**
     * the order to apply this condition. If no argument order is applied we return 0 as
     * indicator that no assignment has been made
     *
     * @return Integer
     */
    public function getOrder()
    {
        return isset($this->args['order'])
            && is_numeric($this->args['order'])
            ? (int)$this->args['order']
            : 0;
    }

    /**
     * get the additional attributes of a condition
     *
     * @return array
     */
    public function getAttributes()
    {
        return (isset($this->args['attributes'])) ? $this->args['attributes'] : [];
    }

    /**
     * the value of this the condition
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->args['value'];
    }

    /**
     * apply condition to total or subtotal
     *
     * @param $totalOrSubTotalOrPrice
     * @return float
     */
    public function applyCondition($totalPrice)
    {
        return $this->apply($totalPrice, $this->args['value']);
    }

    /**
     * apply condition
     *
     * @param $totalOrSubTotalOrPrice
     * @param $conditionValue
     * @return float
     */
    protected function apply($totalOrSubTotalOrPrice, $conditionValue)
    {
        $value = Helpers::normalizePrice($this->cleanValue($conditionValue));
        if ($this->valueisPercentage($conditionValue)) {
            if ($this->valueIsToBeSubtracted($conditionValue)) {
                // $value = Helpers::normalizePrice($this->cleanValue($conditionValue));

                $this->parsedRawValue = $totalOrSubTotalOrPrice * ($value / 100);

                $result = floatval($totalOrSubTotalOrPrice - $this->parsedRawValue);
            } else if ($this->valueIsToBeAdded($conditionValue)) {
                // $value = Helpers::normalizePrice($this->cleanValue($conditionValue));

                $this->parsedRawValue = $totalOrSubTotalOrPrice * ($value / 100);

                $result = floatval($totalOrSubTotalOrPrice + $this->parsedRawValue);
            } else {
                // $value = Helpers::normalizePrice($conditionValue);

                $this->parsedRawValue = $totalOrSubTotalOrPrice * ($value / 100);

                $result = floatval($totalOrSubTotalOrPrice + $this->parsedRawValue);
            }
        } else {
            if ($this->valueIsToBeSubtracted($conditionValue)) {
                $this->parsedRawValue = Helpers::normalizePrice($this->cleanValue($conditionValue));

                $result = floatval($totalOrSubTotalOrPrice - $this->parsedRawValue);
            } else if ($this->valueIsToBeAdded($conditionValue)) {
                $this->parsedRawValue = Helpers::normalizePrice($this->cleanValue($conditionValue));

                $result = floatval($totalOrSubTotalOrPrice + $this->parsedRawValue);
            } else {
                $this->parsedRawValue = Helpers::normalizePrice($conditionValue);

                $result = floatval($totalOrSubTotalOrPrice + $this->parsedRawValue);
            }
        }

        return $result < 0 ? 0.00 : $result;
    }

    /**
     * check if value is a percentage
     *
     * @param $value
     * @return bool
     */
    protected function valueisPercentage($value)
    {
        return (preg_match('/%/', $value) == 1);
    }

    /**
     * check if value is a subtract
     *
     * @param $value
     * @return bool
     */
    protected function valueIsToBeSubtracted($value)
    {
        return (preg_match('/\-/', $value) == 1);
    }

    /**
     * check if value is to be added
     *
     * @param $value
     * @return bool
     */
    protected function valueIsToBeAdded($value)
    {
        return (preg_match('/\+/', $value) == 1);
    }

    /**
     * removes some arithmetic signs (%,+,-) only
     *
     * @param $value
     * @return mixed
     */
    protected function cleanValue($value)
    {
        return str_replace(array('%', '-', '+', '='), '', $value);
    }

    /**
     * validate Item data
     *
     * @param $item
     * @return array $item;
     * @throws InvalidItemConditionException
     */
    protected function validate($item)
    {
        $rules = [
            'name' => ['required'],
            'type' => ['required', 'in:tax,discount,shipping'],
            'target' => ['required'],
            'value' => ['required']
        ];

        $validator = Validator::make($item, $rules);

        if ($validator->fails()) {
            throw new InvalidItemConditionException($validator->messages()->first());
        }

        return $item;
    }

    /**
     * return the array representation of the condition
     *
     * @return array
     */
    public function toArray()
    {
        return $this->args;
    }
}