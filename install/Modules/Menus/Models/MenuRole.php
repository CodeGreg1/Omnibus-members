<?php

namespace Modules\Menus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuRole extends Model
{
    use HasFactory;

    /**
     * @var string $table
     */
    protected $table = 'menu_roles';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'menu_link_id',
        'role_id'
    ];
}
