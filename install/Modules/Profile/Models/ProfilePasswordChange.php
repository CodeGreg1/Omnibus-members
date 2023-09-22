<?php

namespace Modules\Profile\Models;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfilePasswordChange extends Model
{
    use HasFactory;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',
        'last_change'
    ];

    /**
     * @var $timestamps
     */
    public $timestamps = false;
    
    /**
     * Get user that owns the social login
     */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get attribute from date format
     * 
     * @param $input
     *
     * @return string
     */
    public function getLastChangeAttribute($input)
    {
        return Timezone::convertFromUTC($input, auth()->user()->timezone, 'Y-m-d H:i:s');
    }

    /**
     * Get the last change for human readable format
     */
    public function lastChangeForHumans() 
    {
        return Carbon::parse($this->last_change)
            ->settings(['timezone' => auth()->user()->timezone])
            ->diffForHumans();
    }
}
