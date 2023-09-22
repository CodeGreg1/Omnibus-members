<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Cashier\Traits\CashierModeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PricingTable extends Model
{
    use HasFactory, CashierModeScope;

    protected $fillable = ["name", "description", "active", "live"];

    const MAX_PER_GROUP = 4;

    /**
     * Get the items for the pricing table group.
     */
    public function items()
    {
        return $this->hasMany(PricingTableItem::class, 'pricing_table_id')->orderBy('order');
    }

    /**
     * Get total number of packages
     *
     * @return int
     */
    public function packageCount()
    {
        return count(array_unique($this->items->map(function ($item) {
            return $item->price->package_id;
        })->toArray()));
    }

    /**
     * Get list group by packages
     *
     * @return Collection
     */
    public function getPackageList()
    {
        return $this->items->groupBy('price.package_id')->map(function ($items, $i) {
            $prices = $items->map(function ($item) {
                $item->price->allow_promo_code = $item->allow_promo_code;
                return $item->price;
            });

            return (object) [
                'package' => $items->first()->price->package,
                'prices' => $prices
            ];
        });
    }

    /**
     * Get list of prices
     *
     * @return Collextion
     */

    public function table()
    {
        $items = $this->items->sortBy('order')->groupBy('price.package_term_id')->map(function ($items, $i) {
            $term = $items->first()->price->term;
            if (!$term) {
                $term = (object) [
                    'id' => 999,
                    'title' => 'Lifetime'
                ];
            }

            return (object) [
                'id' => $term->id,
                'term' => $term,
                'prices' => $items->map(function ($item) {
                    $item->price->features = $item->price->package->features()->get();
                    return $item;
                })->sortBy('order')
            ];
        })->sortBy('term.id')->values();

        return $items;
    }
}