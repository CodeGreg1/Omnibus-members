<?php

namespace Modules\Modules\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class ModulesLanguagePhraseCreated
{
    use SerializesModels;

    /**
     * @var $modules
     */
    public $modules;

    /**
     * @var $languageKey
     */
    public $languageKey;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($modules, $languageKey)
    {
        $this->modules = $modules;

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
            $this->modules,
            'module language phrase with key: ' . $this->languageKey,
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
