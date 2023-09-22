<?php

namespace Modules\Profile\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialLogin extends Model
{
    use HasFactory;

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'avatar',
        'created_at'
    ];

    /**
     * Always save created_at value
     *
     * @param $value
     * 
     * @return string
     */
    public function setCreatedAtAttribute()
    {
        $this->attributes['created_at'] = now();
    }

    /**
     * Get user that owns the social login
     */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Auth\Database\factories\SocialLoginFactory::new();
    }
}
