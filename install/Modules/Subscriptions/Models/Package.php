<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Support\Carbon;
use Modules\Cashier\Facades\Cashier;
use Illuminate\Database\Eloquent\Model;
use Camroncade\Timezone\Facades\Timezone;
use Modules\Subscriptions\Facades\Product;
use Modules\Cashier\Traits\CashierModeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory, CashierModeScope;

    protected $fillable = [
        "name",
        "primary_heading",
        "secondary_heading",
        "live"
    ];

    /**
     * Append modified field values
     */
    protected $appends = [
        'created'
    ];

    public function getCreatedAttribute($value)
    {
        $timezone = auth()->user() ? auth()->user()->timezone : 'UTC';
        $format = auth()->user() ? auth()->user()->date_format : 'Y m, d';

        return Carbon::parse(
            Timezone::convertFromUTC(
                $this->created_at,
                $timezone
            )
        )->format($format);
    }

    /**
     * Get the prices for the package.
     */
    public function prices()
    {
        return $this->hasMany(PackagePrice::class);
    }

    /**
     * Gateway integrations
     */
    public function gatewayIntegrations()
    {
        return $this->hasMany(PackageIntegration::class, 'package_id', 'id');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'package_package_feature', 'package_id', 'package_feature_id')->withPivot('order')->orderByPivot('order', 'asc');
    }

    /**
     * Get the prices for the package.
     */
    public function moduleLimits()
    {
        return $this->hasMany(PackageModuleLimit::class);
    }

    /**
     * The coupons that belong to the package.
     */
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class);
    }

    /**
     * The package extra conditions
     */
    public function extraConditions()
    {
        return $this->hasMany(PackageExtraCondition::class);
    }

    /**
     * Check if package has a specific feature
     *
     * @return boolean
     */
    public function hasFeature($featureId)
    {
        return $this->features->contains(function ($feature) use ($featureId) {
            return $feature->id === $featureId;
        });
    }

    public function hasPermission($routeName, $decrement = false)
    {
        $permissions = $this->moduleLimits()->get();
        if (!count($permissions)) {
            return false;
        }

        $moduleLimit = $permissions->first(function ($moduleLimit) use ($routeName) {
            return $moduleLimit->permission->name === $routeName;
        });

        if (!$moduleLimit) {
            return false;
        }

        if (!$moduleLimit->limit) {
            return true;
        }

        return !!$moduleLimit->remaining($decrement);
    }

    public function hasSubscription($subscription = null)
    {
        $subscriptions = $this->prices()->with(['subscriptions'])
            ->get()->filter(function ($price) {
                return $price->hasSubscriptions();
            })->pluck('subscriptions');

        if (!$subscriptions->count()) {
            return false;
        }

        if ($subscription) {
            return !!$subscriptions->first(function ($value, $key) use ($subscription) {
                return $value->id === $subscription->id;
            });
        }

        return true;
    }

    public function getGatewayId($gateway)
    {
        $integration = $this->gatewayIntegrations()->where('gateway', $gateway)->first();
        if ($integration) {
            return $integration->id;
        }

        $client = Cashier::client($gateway);
        if (!$client) {
            return null;
        }

        $productService = Product::provider($gateway);
        if (!$productService) {
            return null;
        }

        $integration = $productService->create($this);

        if ($integration) {
            $model = $this->gatewayIntegrations()->create([
                'id' => $integration->id,
                'gateway' => $gateway
            ]);

            return $model->id;
        }

        return null;
    }
}