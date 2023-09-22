<?php

namespace Modules\Profile\Models;

use Modules\Base\Models\Country;
use Modules\Profile\Models\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SessionLocation extends Model
{
    use HasFactory;

    /**
     * @var string $table
     */
    protected $table = 'session_locations';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'session_id',
        'country_id',
        'region',
        'city',
        'zip',
        'address'
    ];

    /**
     * @var array $casts
     */
    protected $casts = [
        'session_id' => 'string',
    ];

    /**
     * Get session for session location
     */
    public function session() 
    {
        return $this->belongsTo(Session::class, 'session_id', 'id');
    }

    /**
     * Get country for session loction
     */
    public function country() 
    {
        return $this->belongsTo(Country::class);
    }
}
