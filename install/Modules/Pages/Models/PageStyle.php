<?php

namespace Modules\Pages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageStyle extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "url"
    ];
}
