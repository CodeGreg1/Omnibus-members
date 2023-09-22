<?php

namespace Modules\Subscriptions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\EmailTemplates\Models\EmailTemplate;
use Modules\Subscriptions\Support\SubscriptionEmailType;
use Modules\Subscriptions\Support\SubscriptionEmailTemplateContent;

class CreateSubscriptionAdminEmailNotificationTableSeeder extends Seeder
{
    /**
     * Subscription email templates
     */
    protected $templates = [
        [
            'name' => SubscriptionEmailType::ADMIN_CREATED,
            'subject' => 'New subscription created',
            'content' => [
                '$$content$$' => '{user} subscribes to <strong>{package} package</strong>.'
            ]
        ],
        [
            'name' => SubscriptionEmailType::ADMIN_CANCELLED,
            'subject' => 'Subscription cancelled',
            'content' => [
                '$$content$$' => '{user} cancelled his/her subscription on <strong>{package} package</strong>.'
            ]
        ],
        [
            'name' => SubscriptionEmailType::ADMIN_EXPIRED,
            'subject' => 'Subscription expired',
            'content' => [
                '$$content$$' => '{user}\'s subscription to <strong>{package} package</strong> has expired.'
            ]
        ],
        [
            'name' => SubscriptionEmailType::ADMIN_PAYMENT_COMPLETED,
            'subject' => 'Subscription payment completed',
            'content' => [
                '$$content$$' => '{user} successfully paid invoice from <strong>{package} package</strong>.'
            ]
        ],
        [
            'name' => SubscriptionEmailType::ADMIN_PAYMENT_FAILED,
            'subject' => 'Subscription payment failed',
            'content' => [
                '$$content$$' => '{user} failed to pay invoice from <strong>{package} package</strong>.'
            ]
        ],
        [
            'name' => SubscriptionEmailType::ADMIN_PACKAGE_CHANGED,
            'subject' => 'User subscription package change',
            'content' => [
                '$$content$$' => '{user}\'s subscription\'s package change to <strong>{package} package</strong>.'
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        foreach ($this->templates as $key => $template) {
            EmailTemplate::create(array_merge(
                $template,
                [
                    'content' => SubscriptionEmailTemplateContent::content($template['content'])
                ]
            ));
        }
    }
}
