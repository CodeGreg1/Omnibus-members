<?php

namespace Modules\Languages\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class LanguagesPhraseCreated
{
    use SerializesModels;

    /**
     * @var string $languageKey
     */
    protected $languageKey;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($languageKey)
    {
        $this->languageKey = $languageKey;

        $this->setLogActivity();
    }

    /**
     * Created log activity
     * 
     * @return mixed
     */
    protected function setLogActivity() 
    {
        event(new LogActivityEvent(
            '',
            'language phrase with key: ' . $this->languageKey,
            'created'
        ));
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
}
