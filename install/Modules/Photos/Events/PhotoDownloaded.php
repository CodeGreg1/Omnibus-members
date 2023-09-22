<?php

namespace Modules\Photos\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class PhotoDownloaded
{
    use SerializesModels;

    public $media;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($media)
    {
        $this->media = $media;

        $this->setLogActivity();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    /**
     * Created log activity
     *
     * @return mixed
     */
    protected function setLogActivity()
    {
        event(new LogActivityEvent(
            $this->media,
            'photo',
            'downloaded'
        ));
    }
}
