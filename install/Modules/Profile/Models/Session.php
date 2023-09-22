<?php

namespace Modules\Profile\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Profile\Models\SessionLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends Model
{
    use HasFactory;

    /**
     * @var string $table
     */
    protected $table = 'sessions';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',
        'country_id',
        'ip_address',
        'user_agent',
        'region_name',
        'city_name',
        'zip_code',
        'address',
        'payload',
        'last_activity'
    ];

    /**
     * @var array $appends
     */
    protected $appends = [
        'browser',
        'os',
        'device'
    ];

    /**
     * @var array $casts
     */
    protected $casts = [
        'id' => 'string',
    ];

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @var array $with
     */
    protected $with = ['location', 'location.country'];

    /**
     * Get browser name
     */
    public function getBrowserAttribute($value)
    {
        $result = new \WhichBrowser\Parser($this->user_agent);

        if (!$result->browser) {
            return '';
        }

        return $result->browser->name;
    }

    /**
     * Get operating system details
     */
    public function getOsAttribute($value)
    {
        $result = new \WhichBrowser\Parser($this->user_agent);

        if (!$result->os) {
            return '';
        }

        if (!$result->os->version) {
            return '';
        }

        return $result->os->name . ' ' . $result->os->version->value;
    }

    /**
     * Get device details
     */
    public function getDeviceAttribute($value)
    {
        $result = new \WhichBrowser\Parser($this->user_agent);

        if (!$result->device) {
            return '';
        }
        
        return $result->device->type;
    }

    /**
     * Get the session location
     */
    public function location() 
    {
        return $this->hasOne(SessionLocation::class, 'session_id', 'id');
    }
}
