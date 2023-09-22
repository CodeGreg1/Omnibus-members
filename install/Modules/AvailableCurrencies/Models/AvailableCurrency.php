<?php

namespace Modules\AvailableCurrencies\Models;

use Modules\Base\Models\Currency;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Traits\WalletBalance;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\AvailableCurrencies\Facades\Currency as FacadesCurrency;

class AvailableCurrency extends Model
{
    use HasFactory, QueryCacheable, WalletBalance;

    /**
     * @var string $table
     */
    protected $table = 'available_currencies';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'currency_id',
        'name',
        'symbol',
        'code',
        'exchange_rate',
        'status',
        'format'
    ];

    /**
     * Append modified field values
     */
    protected $appends = [
        'is_default'
    ];

    /**
     * Specify the amount of time to cache queries.
     * Do not specify or set it to null to disable caching.
     *
     * @var int|\DateTime
     */
    protected $cacheFor = 3600; // cache time, in seconds

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function () {
            static::flushQueryCache();
            FacadesCurrency::clearCache();
        });

        static::updated(function () {
            static::flushQueryCache();
            FacadesCurrency::clearCache();
        });

        static::deleted(function () {
            static::flushQueryCache();
            FacadesCurrency::clearCache();
        });
    }

    /**
     * Get get is default
     *
     * @param $value
     *
     * @return boolean
     */
    public function getIsDefaultAttribute()
    {
        return $this->code == setting(SETTING_CURRENCY_KEY);
    }

    /**
     * Get the currency that owns the available currency
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
