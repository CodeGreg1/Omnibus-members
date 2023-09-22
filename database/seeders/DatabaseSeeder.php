<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Nwidart\Modules\Facades\Module;
use Database\Seeders\CreateMenusSeeder;
use Database\Seeders\CreateRolesSeeder;
use Database\Seeders\CreateUsersSeeder;
use Illuminate\Database\Eloquent\Model;
use Database\Seeders\CreateLanguagesSeeder;
use Database\Seeders\CreatePermissionsSeeder;
use Database\Seeders\CreateDashboardWidgetsSeeder;
use Database\Seeders\CreateAvailableCurrenciesSeeder;
use Modules\Profile\Database\Seeders\SeedProfileCountriesTableSeeder;
use Modules\Tickets\Database\Seeders\TicketCreatedEmailTemplateTableSeeder;
use Modules\Tickets\Database\Seeders\TicketRepliedEmailTemplateTableSeeder;
use Modules\Users\Database\Seeders\CreateUserInvitationEmailTemplateSeederTableSeeder;
use Modules\Tickets\Database\Seeders\TicketStatusChangedManuallyEmailTemplateTableSeeder;
use Modules\Tickets\Database\Seeders\TicketStatusChangedAutomaticallyEmailTemplateTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      Model::unguard();

      $this->call(SeedProfileCountriesTableSeeder::class);
      $this->call(CreateUserInvitationEmailTemplateSeederTableSeeder::class);
      $this->call(CreateLanguagesSeeder::class);
      $this->call(CreateMenusSeeder::class);
      $this->call(CreateAvailableCurrenciesSeeder::class);

    if(Module::has('Subscriptions') && Module::isEnabled('Subscriptions')) {
      $this->call('Modules\Subscriptions\Database\Seeders\SubscriptionsDatabaseSeeder');
    }

      if(Module::has('DashboardWidgets') && Module::isEnabled('DashboardWidgets')) {
        $this->call(CreateDashboardWidgetsSeeder::class);
      }

    if (Module::has('Carts')) {
      $this->call('Modules\Carts\Database\Seeders\CartsDatabaseSeeder');
    }

    if (Module::has('Cashier')) {
      $this->call('Modules\Cashier\Database\Seeders\CashierDatabaseSeeder');
    }

    if (Module::has('Orders')) {
      $this->call('Modules\Orders\Database\Seeders\OrdersDatabaseSeeder');
    }

    if (Module::has('Payments')) {
      $this->call('Modules\Payments\Database\Seeders\PaymentsDatabaseSeeder');
    }

    if (Module::has('Reports')) {
      $this->call('Modules\Reports\Database\Seeders\ReportsDatabaseSeeder');
    }

    if (Module::has('Deposits')) {
      $this->call('Modules\Deposits\Database\Seeders\DepositsDatabaseSeeder');
    }

    if (Module::has('Withdrawals')) {
      $this->call('Modules\Withdrawals\Database\Seeders\WithdrawalsDatabaseSeeder');
    }

    if (Module::has('Transactions')) {
      $this->call('Modules\Transactions\Database\Seeders\TransactionsDatabaseSeeder');
    }

    if (Module::has('Affiliates')) {
      $this->call('Modules\Affiliates\Database\Seeders\AffiliateDatabaseSeeder');
    }

    if (Module::has('Wallet')) {
      $this->call('Modules\Wallet\Database\Seeders\WalletDatabaseSeeder');
    }
    
    if(Module::has('Tickets') && Module::isEnabled('Tickets')) {
        $this->call(TicketCreatedEmailTemplateTableSeeder::class);
        $this->call(TicketRepliedEmailTemplateTableSeeder::class);
        $this->call(TicketStatusChangedAutomaticallyEmailTemplateTableSeeder::class);
        $this->call(TicketStatusChangedManuallyEmailTemplateTableSeeder::class);
    }

    if (Module::has('Photos')) {
      $this->call('Modules\Photos\Database\Seeders\PhotosDatabaseSeeder');
    }

    if (Module::has('Blogs')) {
      $this->call('Modules\Blogs\Database\Seeders\BlogsDatabaseSeeder');
    }

    if (Module::has('Pages')) {
      $this->call('Modules\Pages\Database\Seeders\PagesDatabaseSeeder');
    }

    if (Module::has('Sitemap')) {
      $this->call('Modules\Sitemap\Database\Seeders\SitemapDatabaseSeeder');
    }

    if (Module::has('Frontend')) {
      $this->call('Modules\Frontend\Database\Seeders\FrontendDatabaseSeeder');
    }

    if (Module::has('DatabaseBackup')) {
      $this->call('Modules\DatabaseBackup\Database\Seeders\DatabaseBackupDatabaseSeeder');
    }

    if (Module::has('Maintenance')) {
      $this->call('Modules\Maintenance\Database\Seeders\MaintenanceDatabaseSeeder');
    }

    if (Module::has('System')) {
      $this->call('Modules\System\Database\Seeders\SystemDatabaseSeeder');
    }

    if (Module::has('CategoryTypes')) {
      $this->call('Modules\CategoryTypes\Database\Seeders\CategoryTypesDatabaseSeeder');
    }

    if (Module::has('Categories')) {
      $this->call('Modules\Categories\Database\Seeders\CategoriesDatabaseSeeder');
    }

    if (Module::has('Tickets')) {
      $this->call('Modules\Tickets\Database\Seeders\TicketsDatabaseSeeder');
    }

    $this->call(CreatePermissionsSeeder::class);
    $this->call(CreateRolesSeeder::class);
    $this->call(CreateUsersSeeder::class);

    Model::reguard();
  }
}