<?php

namespace Modules\Withdrawals\Models;

use App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Models\ManualGateway;
use Modules\Base\Support\Media\MediaHelper;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Wallet\Traits\WalletCurrencyScope;
use Modules\Transactions\Traits\Transactionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Withdrawal extends Model implements HasMedia
{
    use HasFactory, Transactionable, InteractsWithMedia, MediaHelper, WalletCurrencyScope;

    protected $fillable = [
        "trx",
        "user_id",
        "method_id",
        "amount",
        "fixed_charge",
        "percent_charge_rate",
        "percent_charge",
        "charge",
        "currency",
        "status",
        "details",
        "rejected_at",
        "reject_reason",
        "note"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'details' => 'array',
        'rejected_at' => 'datetime'
    ];

    /**
     * @var array $appends
     */
    protected $appends = [
        'details'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->trx = strtoupper(uniqid('WID-'));
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'trx';
    }

    /**
     * Handle on registering media conversions
     *
     * @param Media|null $media
     *
     * @return void
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getImageAttribute()
    {
        $items = $this->getMedia('image');

        $items->each(function ($item) {
            $item = $this->mediaUrls($item);
        });

        return $items;
    }

    public function getAdditionalImageAttribute()
    {
        $items = $this->getMedia('additional_image');

        $items->each(function ($item) {
            $item = $this->mediaUrls($item);
        });

        return $items;
    }

    public function getDetailsAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }

        $array = json_decode($value);

        if (!is_array($array)) {
            $array = json_decode(json_decode($value));
        }

        return $array;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function method()
    {
        return $this->belongsTo(ManualGateway::class, 'method_id');
    }
}
