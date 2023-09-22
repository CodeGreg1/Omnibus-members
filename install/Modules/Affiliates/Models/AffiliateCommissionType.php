<?php

namespace Modules\Affiliates\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Affiliates\Facades\CommissionType;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AffiliateCommissionType extends Model
{
    use HasFactory, QueryCacheable;

    protected $fillable = [
        "name",
        "alias",
        "levels",
        "conditions",
        "active"
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function () {
            CommissionType::clearCache();
        });

        static::updated(function () {
            CommissionType::clearCache();
        });

        static::deleted(function () {
            CommissionType::clearCache();
        });
    }
}
