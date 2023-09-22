<?php

namespace Modules\Subscriptions\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Spatie\Permission\Models\Permission;

class PackageModuleLimit extends Model
{
    use HasFactory, QueryCacheable;

    protected $fillable = [
        "package_id",
        "permission_id",
        "limit",
        "term"
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = ['permission', 'counter'];

    protected $cacheFor = 86400;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function () {
            static::flushQueryCache();
        });

        static::updated(function () {
            static::flushQueryCache();
        });

        static::deleted(function () {
            static::flushQueryCache();
        });
    }

    /**
     * Get the permission that owns the package module limit.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * Get the counter for the package module limit
     */
    public function counter()
    {
        return $this->hasOne(UserModuleLimitCounter::class, 'limit_id');
    }

    /**
     * Create initial counter.
     */
    public function initCounter()
    {
        return $this->counter()->updateOrCreate(['limit_id' => $this->id], [
            'count' => 0,
            'date' => now()->format('Y-m-d')
        ]);
    }

    public function remaining($decrement = false)
    {
        $limit = $this->limit;
        $counter = $this->counter;

        if (!$counter) {
            $counter = $this->initCounter();
        }

        $date = Carbon::create($counter->date)->addDay();

        if ($this->term === 'month') {
            $date = Carbon::create($counter->date)->endOfMonth()->addDay();
        }

        if (!$date->isFuture()) {
            $counter = $this->initCounter();
        }

        $count = $counter->count;

        $count = $limit > $counter->count ? $limit - $counter->count : 0;

        if ($decrement && $counter->count < $limit) {
            $counter->count++;
            $counter->save();
        }

        return $count;
    }
}