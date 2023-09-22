<?php

namespace Modules\Subscriptions\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageTerm extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function prices()
    {
        return $this->hasMany(PackagePrice::class, 'package_term_id');
    }

    public function description()
    {
        $str = '';
        if ($this->interval_count > 1) {
            $str .= $this->interval_count . ' ';
        }
        return $str .= Str::plural($this->interval, $this->interval_count);
    }

    /**
     * Get term description
     *
     * @return string
     */
    public function getDescription()
    {
        $str = 'every ' . $this->getTermDiplayLabel();
    }

    /**
     * Get term display label
     *
     * example
     *    ex: 2 days, day, 2 months
     */
    public function getTermDiplayLabel()
    {
        $str = '';
        if ($this->interval_count > 1) {
            $str .= $this->interval_count . ' ';
        }
        $str .= Str::plural($this->interval, $this->interval_count);
        return $str;
    }
}