<?php

namespace Modules\Photos\Models;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Photo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        "name",
        "description"
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $width = $media->getCustomProperty('width');
        $height = $media->getCustomProperty('height');

        $model = $this
            ->addMediaConversion('preview');
        if (($width > 150) && ($height > 150)) {
            $model = $model->fit(Manipulations::FIT_FILL, 150, 150);
        }
        $model = $model->keepOriginalImageFormat();

        if (setting('media_queue_conversions') === '1') {
            $model->queued();
        } else {
            $model->nonQueued();
        }
    }
}
