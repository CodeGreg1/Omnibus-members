<?php

namespace Modules\Subscriptions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Subscriptions\Database\Seeders\CreateSubscriptionMenusTableSeeder;
use Modules\Subscriptions\Database\Seeders\CreateUserSubscriptionMenuTableSeeder;

class SubscriptionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(CreateInvoiceEmailTemplateTableSeeder::class);
        $this->call(CreateSubscriptionUpcomingInvoiceEmailTableSeeder::class);
        $this->call(CreateSubscriptionMenusTableSeeder::class);
        $this->call(CreateUserSubscriptionMenuTableSeeder::class);

        $this->call(CreateSubscriptionCancelledNotificationTableSeeder::class);
        $this->call(CreateSubscriptionCreatedNotificationTableSeeder::class);
        $this->call(CreateSubscriptionExpiredNotificationTableSeeder::class);
        $this->call(CreateSubscriptionPaymentCompletedNotificationTableSeeder::class);
        $this->call(CreateSubscriptionPaymentFailedNotificationTableSeeder::class);
        $this->call(CreateSubscriptionPackageChangeNotificationTableSeeder::class);

        $this->call(AddModuleTableSeeder::class);
        $this->call(CreateSubscriptionAdminEmailNotificationTableSeeder::class);

        Model::reguard();
    }
}