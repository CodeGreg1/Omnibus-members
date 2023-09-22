<?php

namespace Modules\Modules\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class ModulesLanguagePhraseUpdated
{
    use SerializesModels;

    /**
     * @var $modules
     */
    public $modules;

    /**
     * @var $languageCode
     */
    public $languageCode;

    /**
     * @var $languageKey
     */
    public $languageKey;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($modules, $languageCode, $languageKey)
    {
        $this->modules = $modules;

        $this->languageCode = $languageCode;

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
            'module language with '.$this->languageCode.' code and key: ' . $this->languageKey,
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
