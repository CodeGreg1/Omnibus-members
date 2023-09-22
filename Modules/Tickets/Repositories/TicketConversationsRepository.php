<?php

namespace Modules\Tickets\Repositories;

use Modules\Tickets\Models\TicketConversation;
use Modules\Base\Repositories\BaseRepository;

class TicketConversationsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = TicketConversation::class;
}