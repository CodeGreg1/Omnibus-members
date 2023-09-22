<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feature extends Model
{
    use HasFactory;

    protected $table = "package_features";

    protected $fillable = [
        "title",
        "description",
        "ordering"
    ];

    /**
     * The packages that belong to the feature.
     */
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_package_feature', 'package_feature_id', 'package_id');
    }
}