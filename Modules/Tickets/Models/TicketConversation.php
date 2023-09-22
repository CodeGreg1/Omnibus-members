<?php

namespace Modules\Tickets\Models;

use App\Models\User;
use Illuminate\Support\Carbon;
use Modules\Base\Traits\Userable;
use Spatie\MediaLibrary\HasMedia;
use Modules\Tickets\Models\Ticket;
use Modules\Base\Traits\FormattedDate;
use Illuminate\Database\Eloquent\Model;
use Modules\Base\Support\Media\MediaHelper;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TicketConversation extends Model implements HasMedia
{
    use SoftDeletes, 
        HasFactory, 
        QueryCacheable, 
        FormattedDate,
        InteractsWithMedia,
        MediaHelper,
        Userable;

    /**
     * @var string $table
     */
    protected $table = 'ticket_conversations';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',        
        'ticket_id',        
        'message',
        'is_note'
    ];

    /**
     * @var array $appends
     */
    protected $appends = [        
        'attachments',
        'created_at_timeago',
        'created_at_formatted'
    ];

    /**
     * @var int $cacheFor
     */
    protected $cacheFor = 3600;

    /**
     * @var array $with
     */
    protected $with = ['media', 'user'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function () {
            static::flushQueryCache();
        });

        static::updated(function () {
            static::flushQueryCache();
        });

        static::deleted(function () {
            static::flushQueryCache();
        });
    }

    /**
     * Get the created at timeago
     *
     * @param $value
     * 
     * @return string
     */
    public function getCreatedAtTimeagoAttribute()
    {   
        if (is_null($this->created_at)) {
            return 'N/A';
        }

        return Carbon::parse($this->created_at)->diffForHumans();
    }

    /**
     * Get created at formatted
     * 
     * @return string|DateTime
     */
    public function getCreatedAtFormattedAttribute() 
    {
        return $this->formattedCreatedAt();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    /**
     * Handle on registering media conversions
     *
     * @param Media|null $media
     *
     * @return void
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getAttachmentsAttribute()
    {
        $items = $this->getMedia('attachments');
        
        $items->each(function ($item) {
            $item = $this->mediaUrls($item);
        });

        return $items;
    }
}
