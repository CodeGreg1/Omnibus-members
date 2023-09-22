<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Support\AddDashboardMenu;
use Illuminate\Database\Migrations\Migration;

class AddAdminSystemMigrationMenuToMenuLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        (new AddDashboardMenu)->execute([
            'lang' => 'en',
            'label' => 'Database Migration',
            'link' => 'admin.migration.index',
            'icon' => 'fas fa-cog',
        ], 'Admin', 'Database Backup', 'Manage System'); 

        (new AddDashboardMenu)->execute([
            'lang' => 'de',
            'label' => 'Datenbankmigration',
            'link' => 'admin.migration.index',
            'icon' => 'fas fa-cog',
        ], 'Admin', 'Datenbanksicherung', 'System verwalten'); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
