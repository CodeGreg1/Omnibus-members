<?php

namespace Modules\Products\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class ProductsDeleted
{
    use SerializesModels;

    /**
     * @var Illuminate\Database\Eloquent\Model $model
     */
    protected $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
        
        $this->setLogActivity();
        $this->flushQueryCache();
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
     * Flush query cache
     * 
     * @return void
     */
    protected function flushQueryCache() 
    {
        $this->model->flushQueryCache();
    }

    /**
     * Created log activity
     * 
     * @return mixed
     */
    protected function setLogActivity() 
    {   
        // for multi delete
        if(request()->has('ids')) {
            event(new LogActivityEvent(
                '',
                'products',
                'multi deleted with IDs: ' . implode(', ', request()->ids)
            ));
        } else {
            event(new LogActivityEvent(
                $this->model,
                'product',
                'deleted'
            ));
        }
    }
}
