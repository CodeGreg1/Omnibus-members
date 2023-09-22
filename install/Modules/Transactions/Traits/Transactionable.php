<?php

namespace Modules\Transactions\Traits;

use Modules\Transactions\Models\Transaction;

trait Transactionable
{
    /**
     * Get the model's transactions.
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function getTransactionName()
    {
        return class_basename(get_class($this));
    }
}