<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Base\Support\CreateAndAssignPermission;

class AddRoutesForSystemMigrationToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        (new CreateAndAssignPermission)->execute([
            'name' => 'admin.migration.index',
            'display_name' => 'Index Migration'
        ], ['Admin']);

        (new CreateAndAssignPermission)->execute([
            'name' => 'admin.migration.run',
            'display_name' => 'Run Migration'
        ], ['Admin']);
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
