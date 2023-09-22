<?php

namespace Modules\Pages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        "page_id",
        "body"
    ];
}
