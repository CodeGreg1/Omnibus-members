<?php

namespace Modules\Modules\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModuleRelation extends Model
{
    use HasFactory;

    /**
     * @var string $table
     */
    protected $table = 'module_relations';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'module_id',
        'module_related_id'
    ];
}
