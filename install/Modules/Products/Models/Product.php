<?php

namespace Modules\Products\Models;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Modules\Orders\Traits\Orderable;
use Modules\Carts\Traits\Purchasable;
use Modules\Carts\Traits\Checkoutable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Carts\Contracts\PurchasableItem;
use Modules\AvailableCurrencies\Facades\Currency;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements PurchasableItem, HasMedia
{
    use Purchasable, InteractsWithMedia, Checkoutable, Orderable;

    protected $table = "products";

    protected $fillable = [
        "currency",
        "price",
        "title",
        "brand",
        "category",
        "description",
        "rating",
        "stock"
    ];

    /**
     * Model casts
     */
    protected $casts = [
        'rating' => 'string'
    ];

    /**
     * Append modified field values
     */
    protected $appends = [
        'unit_price'
    ];

    /**
     * Relations to eager load
     */
    protected $with = ['media'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    /**
     * get price attribute
     */
    public function getUnitPriceAttribute($value)
    {
        return currency(
            $this->cartPrice(),
            $this->cartCurrency(),
            Currency::getUserCurrency()
        );
    }

    /**
     * The url path to the product view
     *
     * @return string
     */
    public function showPath()
    {
        return route('user.products.show', $this);
    }

    /**
     * the price
     *
     * @return float
     */
    public function cartPrice()
    {
        return $this->price;
    }

    /**
     * the currency
     *
     * @return float
     */
    public function cartCurrency()
    {
        return $this->currency;
    }

    /**
     * the items name
     *
     * @return string
     */
    public function cartItemName()
    {
        return $this->title;
    }

    /**
     * the items name
     *
     * @return string
     */
    public function cartItemDescription()
    {
        return $this->description;
    }

    /**
     * the items preview image
     *
     * @return string
     */
    public function cartImage()
    {
        $image = '/themes/stisla/assets/img/products/product-5-50.png';

        if ($this->media()->count()) {
            $image = $this->media[0]->preview_url;
        }

        return $image;
    }

    /**
     * check if item is shippable
     *
     * @return bool
     */
    public function cartItemShippable()
    {
        return true;
    }

    /**
     * return item stock total
     *
     * @return int
     */
    public function cartStock()
    {
        return $this->stock;
    }
}