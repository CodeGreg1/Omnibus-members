<?php

namespace Modules\Pages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        "order",
        "page_id",
        "heading",
        "sub_heading",
        "description",
        "background_color",
        "media_id",
        "template",
        "data"
    ];

    protected $casts = [
        'data' => 'json'
    ];

    protected $with = ['backgroundImage', 'page'];

    public function backgroundImage()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function getDataAttribute($value)
    {
        if (is_null($value)) {
            return (object) [];
        }

        $result = json_decode($value);

        if (is_array($result)) {
            return (object) $result;
        }

        return json_decode($result);
    }
}
