<?php

namespace Modules\Subscriptions\Models;

use LogicException;
use Illuminate\Support\Carbon;
use Modules\Cashier\Facades\Cashier;
use Modules\Payments\Traits\Payable;
use Illuminate\Database\Eloquent\Model;
use Camroncade\Timezone\Facades\Timezone;
use Modules\Cashier\Traits\CashierModeScope;
use Modules\Subscriptions\Traits\HasDiscount;
use Modules\Subscriptions\Traits\ManageTotal;
use Modules\Subscriptions\Traits\ManagePayment;
use Modules\Payments\Contracts\PayableInterface;
use Modules\Transactions\Traits\Transactionable;
use Modules\AvailableCurrencies\Facades\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Subscriptions\Traits\HasEmailNotifications;
use Modules\Subscriptions\Services\SubscriptionCompletion;
use Modules\Subscriptions\Traits\HasPackageExtraCondition;

class Subscription extends Model implements PayableInterface
{
    use
        HasFactory,
        Payable,
        HasDiscount,
        ManagePayment,
        ManageTotal,
        CashierModeScope,
        HasPackageExtraCondition,
        Transactionable,
        HasEmailNotifications;

    protected $fillable = [
        "ref_profile_id",
        "package_price_id",
        "gateway",
        "name",
        "description",
        "recurring",
        "trialing",
        "trial_ends_at",
        "starts_at",
        "ends_at",
        "ended_at",
        "cancels_at",
        "canceled_at",
        "cancels_at_end",
        "meta",
        "note",
        "live",
        "canceled_notified_email",
        "expired_notified_email",
        "total_cycles_completed"
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELED = 'cancelled';
    const STATUS_INCOMPLETE = 'incomplete';
    const STATUS_EXPIRED = 'ended';
    const STATUS_PAST_DUE = 'past_due';
    const STATUS_TRIALING = 'trialing';
    const STATUS_UNPAID = 'unpaid';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancels_at' => 'datetime',
        'canceled_at' => 'datetime',
        'ended_at' => 'datetime'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = ['subscribable', 'item', 'discount'];

    /**
     * Append modified field values
     */
    protected $appends = [
        'created_at_display',
        'ends_at_display',
        'trial_ends_at_display'
    ];

    /**
     * Get the parent subscriberable model.
     */
    public function subscribable()
    {
        return $this->morphTo();
    }

    /**
     * Get the items for the subscription.
     */
    public function items()
    {
        return $this->hasMany(SubscriptionItem::class);
    }

    /**
     * Get the subscription's item.
     */
    public function item()
    {
        return $this->hasOne(SubscriptionItem::class)->latestOfMany();
    }

    /**
     * Get the start and end date of the subscription
     *
     * @return array
     */
    public function getPeriod()
    {
        if ($this->isRecurring() && $this->ends_at) {
            $start = $this->ends_at->sub(
                $this->item->price->term->interval_count,
                $this->item->price->term->interval
            )->toUserTimezone();

            return [
                'start' => $start,
                'end' => $this->ends_at->toUserTimezone()
            ];
        }

        return null;
    }

    /**
     * Get the next billing date of the subscription
     *
     * @return Carbon|null
     */
    public function getNextBillingDate()
    {
        if ($this->isRecurring() && $this->ends_at) {
            return  $this->ends_at->toUserTimezone()->toUserFormat();
        }

        return null;
    }

    /**
     * Get unit price of the package price
     * @param bool $formatted
     * @param string $currency
     * @return string|float
     */
    public function getUnitPrice($formatted = true, $currency = null)
    {
        $currency = $currency ? $currency : Currency::getUserCurrency();

        $price = $this->item->price->getUnitPrice(false, $currency);

        if (!$formatted) {
            return $price;
        }

        return currency_format(
            $price,
            $currency
        );
    }

    /**
     * Get the package name
     *
     * @return string
     */
    public function getPackageName()
    {
        return $this->item->price->package->name;
    }

    public function getTermDescription()
    {
        if ($this->isRecurring()) {
            return $this->item->price->term->description . ' Billing';
        }

        return 'Lifetime';
    }

    public function getStatus()
    {
        if ($this->canceled()) {
            return $this::STATUS_CANCELED;
        }

        if ($this->ended_at && !$this->ended_at->isFuture()) {
            return $this::STATUS_EXPIRED;
        }

        if ($this->ends_at) {
            if ($this->ends_at->isFuture()) {
                if ($this->onTrial()) {
                    return $this::STATUS_TRIALING;
                }

                return $this::STATUS_ACTIVE;
            }

            if ($this->onGracePeriod()) {
                return $this::STATUS_PAST_DUE;
            }

            return $this::STATUS_EXPIRED;
        }

        return $this::STATUS_ACTIVE;
    }

    public function getStatusTitle()
    {
        $status = $this->getStatus();

        return str($status)->replace('_', ' ')->title();
    }

    /**
     * Get the status_display attribute
     */
    public function getStatusDisplay()
    {
        $status = $this->getStatus();

        if ($status === $this::STATUS_TRIALING) {
            $year = $this->trial_ends_at
                ->toUserTimezone()
                ->format('Y');
            $value = __('Trial ends ') . $this->trial_ends_at
                ->toUserTimezone()
                ->format('M d');
            if (now()->format('Y') !== $year) {
                $value .= ' ' . $year;
            }
            return $value;
        }

        if ($status === $this::STATUS_CANCELED) {
            if ($this->onGracePeriod()) {
                return __('Active until :date', [
                    'date' => $this->getGracePeriodEndDate()->toUserTimezone()->toUserFormat()
                ]);
            }

            return __('Cancelled last :date', [
                'date' => $this->canceled_at->toUserTimezone()->toUserFormat()
            ]);
        }

        if ($status === $this::STATUS_PAST_DUE) {
            return __('Payment pending');
        }

        if ($status === $this::STATUS_EXPIRED) {
            $endsAt = $this->getGracePeriodEndDate()->toUserTimezone()->toUserFormat();
            if ($this->onGracePeriod()) {
                return __('Active until :date', [
                    'date' => $endsAt
                ]);
            } else {
                return __('Ended last :date', [
                    'date' => $endsAt
                ]);
            }
        }

        return __('Active');
    }

    /**
     * Get the created_at_discplay attribute
     */
    public function getCreatedAtDisplayAttribute($value)
    {
        return $this->created_at->toUserTimezone()->toUserFormat();
    }

    /**
     * Get the end_at_display attribute
     */
    public function getEndsAtDisplayAttribute($value)
    {
        return $this->ended_at ? $this->ended_at->toUserTimezone()->toUserFormat() : '--';
    }

    /**
     * Get the trial_ends_at_display attribute
     */
    public function getTrialEndsAtDisplayAttribute($value)
    {
        return $this->trial_ends_at
            ? $this->trial_ends_at->toUserTimezone()->toUserFormat()
            : '--';
    }

    /**
     * Determine if the subscription is within its trial period.
     *
     * @return bool
     */
    public function onTrial()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Get the remaining days of the trial
     *
     * @return int
     */
    public function trialCyclesRemaining()
    {
        if (!$this->onTrial()) {
            return 0;
        }

        return now()->startOfDay()->diffInDays($this->trial_ends_at);
    }

    /**
     * Get the total cycles of the trial
     *
     * @return int
     */
    public function trialTotalCycles()
    {
        if (!$this->onTrial()) {
            return 0;
        }

        return $this->starts_at->startOfDay()->diffInDays($this->trial_ends_at->endOfDay());
    }

    /**
     * Get the total days trial count
     *
     * @return int
     */
    public function trialCyclesCompleted()
    {
        if (!$this->onTrial()) {
            return 0;
        }

        $total = $this->trialTotatCycles();
        $remaining = $this->trialCyclesRemaining();

        return $total > $remaining ? $total - $remaining : 0;
    }

    /**
     * Filter query by on trial.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeOnTrial($query)
    {
        $query->whereNotNull('trial_ends_at')->where('trial_ends_at', '>', Carbon::now())->whereNull('canceled_at');
    }

    /**
     * Force the trial to end immediately.
     *
     * This method must be combined with swap, resume, etc.
     *
     * @return $this
     */
    public function skipTrial()
    {
        $this->trial_ends_at = null;

        return $this;
    }


    /**
     * Mark the subscription as canceled.
     *
     * @return void
     *
     * @internal
     */
    public function markAsCanceled()
    {
        $date = now();

        $this->fill([
            'cancels_at' => $date,
            'canceled_at' => $date
        ])->save();
    }

    /**
     * Mark the subscription as expired.
     *
     * @return void
     *
     * @internal
     */
    public function markAsExpired()
    {
        $this->fill([
            'ended_at' => now(),
        ])->save();
    }

    /**
     * Determine if the subscription has a specific product.
     *
     * @param  string  $product
     * @return bool
     */
    public function hasProduct($product)
    {
        return $this->package_id === $product;
    }

    /**
     * Determine if the subscription is active, on trial, or within its grace period.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->onGracePeriod();
    }

    /**
     * Determine if the subscription is within its grace period after cancellation.
     *
     * @return bool
     */
    public function onGracePeriod()
    {
        if (is_null($this->ends_at)) {
            return true;
        }

        $extension = intval(setting('subscription_expiration_extension'));
        if ($extension) {
            return $this->ends_at->addDays($extension)->isFuture();
        }

        return $this->ends_at->isFuture();
    }

    public function getGracePeriodEndDate()
    {
        if (is_null($this->ends_at)) {
            return now();
        }

        $extension = intval(setting('subscription_expiration_extension'));
        if ($extension) {
            return $this->ends_at->addDays($extension);
        }

        return $this->ends_at;
    }

    public function getLastEndedTotalHours()
    {
        if (is_null($this->ends_at)) {
            return 0;
        }

        if ($this->onGracePeriod()) {
            return 0;
        }

        $extension = intval(setting('subscription_expiration_extension'));

        return now()->addDays($extension)->diffInHours($this->ends_at);
    }

    /**
     * Determine if the subscription has ended and the grace period has expired.
     *
     * @return bool
     */
    public function ended()
    {
        if (!is_null($this->canceled_at)) {
            return false;
        }

        if (
            $this->ended_at
            && !$this->ended_at->isFuture()
        ) {
            return true;
        }

        if ($this->ends_at) {
            if ($this->onGracePeriod()) {
                return false;
            }

            return true;
        }

        return false;

        // if ($this->ended_at && !$this->ended_at->isFuture()) {
        //     return true;
        // }

        // return false;
    }

    /**
     * Determine if the subscription is no longer active.
     *
     * @return bool
     */
    public function canceled()
    {
        return !!$this->canceled_at && !$this->canceled_at->isFuture();
    }

    public function isRecurring()
    {
        return $this->recurring;
    }

    public function isManual()
    {
        return $this->gateway === 'wallet';
    }

    public function isLifetime()
    {
        return !$this->recurring;
    }

    public function completion()
    {
        return new SubscriptionCompletion($this);
    }

    /**
     * Determine if the subscription is active.
     *
     * @return bool
     */
    public function active()
    {
        return $this->onGracePeriod();
    }

    /**
     * Filter query by on grace period.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeOnGracePeriod($query)
    {
        $date = Carbon::now();
        // Extension
        $extension = intval(setting('subscription_expiration_extension'));
        if ($extension) {
            $date = $date->subDays($extension);
        }

        $query->whereNotNull('ends_at')->where('ends_at', '>', $date);
    }

    /**
     * Filter query by not on grace period.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeNotOnGracePeriod($query)
    {
        $date = Carbon::now();
        // Extension
        $extension = intval(setting('subscription_expiration_extension'));
        if ($extension) {
            $date = $date->subDays($extension);
        }
        $query->whereNull('ends_at')->orWhere('ends_at', '<=', $date);
    }

    /**
     * Filter query by active status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeActive($query)
    {
        $date = Carbon::now();
        // Extension
        $extension = intval(setting('subscription_expiration_extension'));
        if ($extension) {
            $date = $date->subDays($extension);
        }

        return $query->orWhere('ends_at', '>', $date)->orWhereNull('ends_at');
    }

    public function getStatusLabel()
    {
        $label = 'secondary';
        $status = $this->getStatus();
        switch ($status) {
            case $this::STATUS_ACTIVE:
                $label = 'primary';
                break;
            case $this::STATUS_INCOMPLETE:
                $label = 'secondary';
            case $this::STATUS_EXPIRED:
                $label = 'danger';
                break;
            case $this::STATUS_TRIALING:
                $label = 'info';
                break;
            case $this::STATUS_PAST_DUE:
                $label = 'success';
                break;
            case $this::STATUS_UNPAID:
            case $this::STATUS_CANCELED:
                $label = 'warning';
                break;
            default:
                break;
        }

        return $label;
    }

    /**
     * Determine if the subscription has a specific price.
     *
     * @param  string  $price
     * @return bool
     */
    public function hasPrice($price)
    {
        return $this->item->package_price_id === $price;
    }

    public function cancel($mode = 'ends_at', $updateEntity = true)
    {
        if ($this->isRecurring()) {
            if ($this->gateway === 'wallet' || $this->gateway === 'manual') {
                $date = now();
                $this->fill([
                    'ends_at' => $date,
                    'cancels_at' => $date,
                    'canceled_at' => $date
                ])->save();
            } else {
                $response = Cashier::client($this->gateway)->subscriptions->cancel($this);
                if (!$response) {
                    return null;
                }

                if ($response && $updateEntity) {
                    $date = now();
                    $this->fill([
                        'cancels_at' => $mode === 'now' ? $date : $this->ends_at,
                        'canceled_at' => $date,
                        'ends_at' => $mode === 'now' ? $date : $this->ends_at
                    ])->save();
                }
            }
        } else {
            if ($updateEntity) {
                $date = now();
                $this->fill([
                    'ends_at' => $date,
                    'cancels_at' => $date,
                    'canceled_at' => $date
                ])->save();
            }
        }

        return $this->fresh();
    }

    /**
     * Update the underlying subscription information for the model.
     *
     * @param  array  $options
     * @return mixed|null
     */
    public function updateStripeSubscription(array $options = [])
    {
        return $this->user->cashier($this->gateway)->subscriptions->update(
            $this->gateway_id,
            $options
        );
    }

    /**
     * Resume the canceled subscription.
     *
     * @return $this
     *
     * @throws \LogicException
     */
    public function resume()
    {
        if ($this->isRecurring()) {
            if (!$this->onGracePeriod()) {
                throw new LogicException(__('Unable to resume subscription that is not within grace period.'));
            }

            $stripeSubscription = $this->updateStripeSubscription([
                'cancel_at_period_end' => false,
                'trial_end' => $this->onTrial() ? $this->trial_ends_at->getTimestamp() : 'now',
            ]);

            // Finally, we will remove the ending timestamp from the user's record in the
            // local database to indicate that the subscription is active again and is
            // no longer "canceled". Then we shall save this record in the database.
            $this->fill([
                'status' => $stripeSubscription->status,
                'ends_at' => null,
            ])->save();
        } else {
            $this->fill([
                'status' => $this::STATUS_ACTIVE,
                'ends_at' => null,
            ])->save();
        }

        return $this;
    }

    public function invoiceItems()
    {
        $description = 'Lifetime payment';
        $period = $this->getPeriod();
        if ($period) {
            if ($period['start']->format('Y') !== $period['end']->format('Y')) {
                $description = $period['start']->format('M d, Y');
                $description .= ' - ';
                $description .= $period['end']->format('M d, Y');
            } else {
                $description = $period['start']->format('M d');
                $description .= ' - ';
                $description .= $period['end']->format('M d, Y');
            }
        }

        return collect([
            (object) [
                'title' => $this->getPackageName(),
                'description' => $description,
                'quantity' => 1,
                'price' => $this->getUnitPrice(),
                'total' => $this->getTotal(),
                'item' => $this
            ]
        ]);
    }

    /**
     * Get path for payable
     *
     * @return string
     */
    public function payablePath()
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return route('admin.subscriptions.show', $this);
        }

        return route('user.subscriptions.show', $this);
    }

    /**
     * Get invoice attributes for email
     *
     * @return array
     */
    public function invoiceEmailAttributes()
    {
        $period = $this->getPeriod();
        $invoiceDesc = $this->getUnitPrice(true) . ' due ' . $period['start']->formatLocalized('%B %e, %Y');

        $coverageDescription = '';
        if ($period) {
            if ($period['start']->format('Y') !== $period['end']->format('Y')) {
                $coverageDescription = $period['start']->formatLocalized('%B %e, %Y');
                $coverageDescription .= ' - ';
                $coverageDescription .= $period['end']->formatLocalized('%B %e, %Y');
            } else {
                $coverageDescription = $period['start']->formatLocalized('%B %e');
                $coverageDescription .= ' - ';
                $coverageDescription .= $period['end']->formatLocalized('%B %e, %Y');
            }
        }

        return [
            'due_date' => $period['start']->formatLocalized('%B %e, %Y'),
            'invoice_description' => $invoiceDesc,
            'coverage_description' => $coverageDescription,
            'plan_name' => $this->getPackageName(),
            'quantity' => 1
        ];
    }

    /**
     * Retrieve subscription resource from gateway
     *
     * @return Object
     */
    public function getClientResource()
    {
        return Cashier::client($this->gateway)->subscriptions->retrieve($this->ref_profile_id);
    }

    /**
     * Add key value pair to meta column
     *
     * @param string $key
     * @param string $value
     */
    public function addMeta($key, $value)
    {
        $meta = (array) json_decode($this->meta);
        if ($meta) {
            $meta[$key] = $value;
            $this->fill([
                'meta' => json_encode((object) $meta)
            ])->save();
        } else {
            $this->meta = json_encode((object)[
                [$key] => $value
            ]);
            $this->save();
        }

        return $this;
    }

    /**
     * Retrieve a value from meta column
     *
     * @param string $key
     */
    public function getMeta($key = null)
    {
        $meta = (array) json_decode($this->meta);
        if ($meta) {
            if (is_null($key)) {
                return $meta;
            }
            return $meta[$key] ?? null;
        }

        return null;
    }
}