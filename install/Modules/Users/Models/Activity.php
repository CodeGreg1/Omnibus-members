<?php

namespace Modules\Users\Models;
use Illuminate\Support\Carbon;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as ActivityBase;

class Activity extends ActivityBase
{
    use HasFactory;

    /**
     * @param array $with
     */
    public $with = ['causer', 'causer.roles'];

    /**
     * Append modified field values
     */
    protected $appends = [
        'created_at_for_humans'
    ];

    /**
     * Get attribute from date to readable for humans
     * 
     * @return string
     */
    public function getCreatedAtForHumansAttribute() 
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
