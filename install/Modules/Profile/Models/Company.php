<?php

namespace Modules\Profile\Models;

use App\Models\User;
use Modules\Base\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    /**
     * @var string $table
     */
    protected $table = 'companies';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'number',
        'tax_number',
        'phone'
    ];

    /**
     * Get all of the address for the company.
     */
    public function addresses()
    {
        return $this->morphToMany(Address::class, 'addressable');
    }

    /**
     * Get user owns the company
     */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the address record associated with the company. 
     */
    public function address() 
    {
        return $this->belongsTo(Address::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Profile\Database\factories\CompanyFactory::new();
    }
}
