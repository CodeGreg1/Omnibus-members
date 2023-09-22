<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserModuleLimitCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        "limit_id",
        "count",
        "date"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date'
    ];
}