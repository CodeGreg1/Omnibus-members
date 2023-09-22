<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Base\Support\UpdateAndAssignPermission;

class ChangeAffilitiateSettingRouteNameToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        (new UpdateAndAssignPermission)->execute(
            'admin.settings.affiliates',
            'admin.settings.affiliates.index'
        );
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
