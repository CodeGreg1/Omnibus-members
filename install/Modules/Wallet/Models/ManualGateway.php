<?php

namespace Modules\Wallet\Models;

use Modules\Deposits\Models\Deposit;
use Illuminate\Database\Eloquent\Model;
use Modules\Withdrawals\Models\Withdrawal;
use Modules\Wallet\Traits\WalletCurrencyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ManualGateway extends Model
{
    use HasFactory, WalletCurrencyScope;

    protected $table = "manual_gateway_methods";

    protected $fillable = [
        "type",
        "name",
        "min_limit",
        "max_limit",
        "delay",
        "fixed_charge",
        "percent_charge",
        "currency",
        "user_data",
        "instructions",
        "status",
        "media_id"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'min_limit' => 'float',
        'max_limit' => 'float',
        'fixed_charge' => 'float',
        'percent_charge' => 'float',
        'user_data' => 'json'
    ];

    /**
     * @var array $appends
     */
    protected $appends = [
        'user_data'
    ];

    /**
     * @var array $with
     */
    protected $with = ['logo', 'logo.model'];

    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'method_id', 'id');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'method_id', 'id');
    }

    public function logo()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function getUserDataAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        return json_decode(json_decode($value));
    }

    public function getPreviewImage()
    {
        if (!$this->logo) {
            return '/upload/media/default/file-preview.png';
        }

        return $this->logo->original_url;
    }
}
