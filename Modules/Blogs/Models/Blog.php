<?php

namespace Modules\Blogs\Models;

use App\Models\User;
use Modules\Tags\Models\Tag;
use Spatie\ModelStates\HasStates;
use Modules\Blogs\States\StatusState;
use Illuminate\Database\Eloquent\Model;
use Modules\Categories\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Blog extends Model
{
    use HasFactory, HasStates;

    protected $fillable = [
        "user_id",
        "slug",
        "title",
        "description",
        "page_title",
        "page_description",
        "media_id",
        "category_id",
        "content",
        "status",
        "views",
        "modified_at"
    ];

    protected $casts = [
        'status' => StatusState::class
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * Get all of the tags for the post.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
