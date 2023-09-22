<?php

namespace Modules\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'name',
        'symbol',
        'symbol_native',
        'decimal_digits',
        'rounding',
        'code',
        'name_plural',
        'format'
    ];

    public $timestamps = false;
}
