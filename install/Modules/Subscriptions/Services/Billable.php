<?php

namespace Modules\Subscriptions\Services;

use Modules\Subscriptions\Traits\Subscribable;
use Modules\Subscriptions\Services\Concerns\ManagesInvoices;
use Modules\Subscriptions\Services\Concerns\ManagesSubscriptions;

trait Billable
{
    use Subscribable;
    use ManagesInvoices;
    use ManagesSubscriptions;
}