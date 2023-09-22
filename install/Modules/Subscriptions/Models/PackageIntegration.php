<?php

namespace Modules\Subscriptions\Models;

use Modules\Cashier\Facades\Cashier;
use Modules\Cashier\Services\Client;
use Illuminate\Database\Eloquent\Model;
use Modules\Cashier\Traits\CashierModeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageIntegration extends Model
{
    use HasFactory, CashierModeScope;

    protected $table = "package_gateways";

    protected $fillable = [
        "id",
        "gateway",
        "package_id",
        "live"
    ];

    public $incrementing = false;

    public $timestamps = false;

    /**
     * Get the gateway integration client
     *
     * @return Client $client
     */
    public function getClient()
    {
        return Cashier::client($this->gateway)->service;
    }
}
