<?php

namespace Modules\Subscriptions\Traits;

use Modules\EmailTemplates\Services\Mailer;
use Modules\Subscriptions\Support\SubscriptionEmailType;

trait HasEmailNotifications
{
    public function sendEmailOnExpired($send = false)
    {
        if (!$this->expired_notified_email || $send) {
            // Notify user
            if (setting('subscription_expired_email_status') === 'enable') {
                try {
                    (new Mailer)->template(SubscriptionEmailType::EXPIRED)
                        ->to($this->subscribable->email)
                        ->with([
                            'name' => $this->subscribable->getName(),
                            'package' => $this->getPackageName()
                        ])
                        ->send();
                } catch (\Exception $e) {
                    report($e);
                }
            }

            // Notify all admin
            if (setting('admin_subscription_expired_email_status') === 'enable') {
                try {
                    (new Mailer)->template(SubscriptionEmailType::ADMIN_EXPIRED)
                        ->to(EMAIL_ADMINS)
                        ->with([
                            'package' => $this->getPackageName(),
                            'user' => $this->subscribable->present()->name
                        ])
                        ->send();
                } catch (\Exception $e) {
                    report($e);
                }
            }

            if (!$this->expired_notified_email) {
                $this->fill([
                    'expired_notified_email' => 1
                ])->save();
            }
        }
    }

    public function sendEmailOnCanceled($send = false)
    {
        if (!$this->canceled_notified_email || $send) {
            // Notify user
            if (setting('subscription_cancelled_email_status') === 'enable') {
                try {
                    (new Mailer)->template(SubscriptionEmailType::CANCELLED)
                        ->to($this->subscribable->email)
                        ->with([
                            'name' => $this->subscribable->getName(),
                            'package' => $this->getPackageName()
                        ])
                        ->send();
                } catch (\Exception $e) {
                    report($e);
                }
            }

            // Notify all admin
            if (setting('admin_subscription_cancelled_email_status') === 'enable') {
                try {
                    (new Mailer)->template(SubscriptionEmailType::ADMIN_CANCELLED)
                        ->to(EMAIL_ADMINS)
                        ->with([
                            'package' => $this->getPackageName(),
                            'user' => $this->subscribable->present()->name
                        ])
                        ->send();
                } catch (\Exception $e) {
                    report($e);
                }
            }

            if (!$this->canceled_notified_email) {
                $this->fill([
                    'canceled_notified_email' => 1
                ])->save();
            }
        }
    }

    public function sendEmailOnCreated()
    {
        // Notify user
        if (setting('subscription_success_email_status') === 'enable') {
            try {
                (new Mailer)->template(SubscriptionEmailType::CREATED)
                    ->to($this->subscribable->email)
                    ->with([
                        'name' => $this->subscribable->getName(),
                        'package' => $this->getPackageName()
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }

        // Notify all admin
        if (setting('admin_subscription_success_email_status') === 'enable') {
            try {
                (new Mailer)->template(SubscriptionEmailType::ADMIN_CREATED)
                    ->to(EMAIL_ADMINS)
                    ->with([
                        'package' => $this->getPackageName(),
                        'user' => $this->subscribable->present()->name
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }

    public function sendEmailOnPackageChange()
    {
        // Notify user
        if (setting('subscription_success_email_status') === 'enable') {
            try {
                (new Mailer)->template(SubscriptionEmailType::PACKAGE_CHANGE)
                    ->to($this->subscribable->email)
                    ->with([
                        'name' => $this->subscribable->getName(),
                        'package' => $this->getPackageName()
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }

        // Notify all admin
        if (setting('admin_subscription_package_change_status') === 'enable') {
            try {
                (new Mailer)->template(SubscriptionEmailType::ADMIN_PACKAGE_CHANGED)
                    ->to(EMAIL_ADMINS)
                    ->with([
                        'package' => $this->getPackageName(),
                        'user' => $this->subscribable->present()->name
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }

    public function sendEmailOnPaymentFailed()
    {
        // Notify user
        if (setting('subscription_payment_failed_email_status') === 'enable') {
            try {
                (new Mailer)->template(SubscriptionEmailType::PAYMENT_FAILED)
                    ->to($this->subscribable->email)
                    ->with([
                        'name' => $this->subscribable->getName(),
                        'package' => $this->getPackageName(),
                        'amount' => $this->getTotal(
                            true,
                            $this->subscribable->currency
                        )
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }

        // Notify all admin
        if (setting('admin_subscription_payment_failed_email_status') === 'enable') {
            try {
                (new Mailer)->template(SubscriptionEmailType::ADMIN_PAYMENT_FAILED)
                    ->to(EMAIL_ADMINS)
                    ->with([
                        'package' => $this->getPackageName(),
                        'user' => $this->subscribable->present()->name
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }

    public function sendEmailOnPaymentCompleted()
    {
        // Notify user
        if (setting('subscription_payment_completed_email_status') === 'enable') {
            try {
                (new Mailer)->template(SubscriptionEmailType::PAYMENT_COMPLETED)
                    ->to($this->subscribable->email)
                    ->with([
                        'name' => $this->subscribable->getName(),
                        'package' => $this->getPackageName(),
                        'amount' => $this->getTotal(
                            true,
                            $this->subscribable->currency
                        )
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }

        // Notify all admin
        if (setting('admin_subscription_payment_completed_email_status') === 'enable') {
            try {
                (new Mailer)->template(SubscriptionEmailType::ADMIN_PAYMENT_COMPLETED)
                    ->to(EMAIL_ADMINS)
                    ->with([
                        'package' => $this->getPackageName(),
                        'user' => $this->subscribable->present()->name
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
