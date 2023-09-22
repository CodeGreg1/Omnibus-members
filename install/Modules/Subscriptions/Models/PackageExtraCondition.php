<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageExtraCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        "package_id",
        "name",
        "description",
        "shortcode",
        "value"
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
