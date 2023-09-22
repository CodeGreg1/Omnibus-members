<?php

namespace Modules\Base\Models;

use App\Models\User;
use Modules\Base\Models\Country;
use Modules\Profile\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    /**
     * @var string $table
     */
    protected $table = 'addresses';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'country_id',
        'name',
        'description',
        'address_1',
        'address_2',
        'city',
        'state',
        'zip_code'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime'
    ];

    protected $with = ['country'];

    /**
     * Get attribute from date format
     *
     * @param $input
     *
     * @return string
     */
    public function getCreatedAtAttribute($input)
    {
        return Timezone::convertFromUTC($input, auth()->user()->timezone, 'Y-m-d H:i:s');
    }

    /**
     * Get all of the posts that are assigned this tag.
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'addressable');
    }

    /**
     * Get all of the videos that are assigned this tag.
     */
    public function companies()
    {
        return $this->morphedByMany(Company::class, 'addressable');
    }

    /**
     * Get country own this address
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
