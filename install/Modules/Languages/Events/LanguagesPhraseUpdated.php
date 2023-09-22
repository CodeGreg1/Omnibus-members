<?php

namespace Modules\Languages\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class LanguagesPhraseUpdated
{
    use SerializesModels;

    /**
     * @var model $language
     */
    protected $language;

    /**
     * @var string $languageKey
     */
    protected $languageKey;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($language, $languageKey)
    {
        $this->language = $language;
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
            $this->language,
            'language with key: ' . $this->languageKey,
            'updated'
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
