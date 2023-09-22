<?php

namespace Modules\Tickets\Models;

use App\Models\User;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Modules\Base\Traits\FormattedDate;
use Modules\Tickets\Traits\Numberable;
use Illuminate\Database\Eloquent\Model;
use Modules\Categories\Models\Category;
use Modules\Base\Traits\Multitenantable;
use Modules\Tickets\Support\TicketStatus;
use Modules\Base\Support\Media\MediaHelper;
use Modules\Tickets\Support\TicketPriority;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Tickets\Models\TicketConversation;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Ticket extends Model implements HasMedia
{
    use Numberable,
        SoftDeletes, 
        HasFactory, 
        MediaHelper,
        FormattedDate,
        QueryCacheable, 
        Multitenantable,
        InteractsWithMedia;

    /**
     * @var string $table
     */
    protected $table = 'tickets';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'number',        
        'user_id',        
        'category_id',        
        'subject',        
        'priority',        
        'message',        
        'status',        
        'rating',
    ];

    /**
     * @var string SETTING_NAME_STATUS_COUNTER
     */
    public const SETTING_NAME_STATUS_COUNTER = 'ticket_status_counter';

	/**
     * @var array RATING_RADIO
     */
    public const RATING_RADIO = [
    	'excellent' => 'Excellent',	
        'very_good' => 'Very Good',
        'good' => 'Good',
        'neutral' => 'Neutral',
        'bad' => 'Bad',
        'very_bad' => 'Very Bad',
        'horrible' => 'Horrible'
    ];

    /**
     * @var array $appends
     */
    protected $appends = [        
        'attachments',
        'created_at_timeago',
        'created_at_formatted',
        'status_details',
        'priority_details'
    ];

    /**
     * @var int $cacheFor
     */
    protected $cacheFor = 10800;

    /**
     * @var array $with
     */
    protected $with = ['media'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->status = TicketStatus::OPEN;
        });

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
     * Get the ticket's priority details
     * 
     * @return string
     */
    public function getPriorityDetailsAttribute()
    {
        return TicketPriority::get($this->priority) ?? [];
    }

    /**
     * Get the ticket's status name
     * 
     * @return array
     */
    public function getStatusDetailsAttribute()
    {
        return TicketStatus::get($this->status) ?? [];
    }

    /**
     * Get the ticket's rating
     *
     * @param $value
     * 
     * @return string
     */
    public function getRatingAttribute($value)
    {
        return self::RATING_RADIO[$value] ?? '';
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

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function conversations()
    {
        return $this->hasMany(TicketConversation::class, 'ticket_id', 'id');
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

    public function totalResponseTime() 
    {
        return $this->getModel()->with([
            'conversations' => function($query) {
                $query->orderBy('created_at', 'asc');
            }
        ])->first();
    }
}
