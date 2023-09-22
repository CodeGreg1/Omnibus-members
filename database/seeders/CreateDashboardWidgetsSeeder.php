<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DashboardWidgets\Models\DashboardWidget;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateDashboardWidgetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DashboardWidget::create([
            'name' => 'Total Users',
            'type' => 'counter',
            'attributes' => json_encode([
                'title' => 'Total Users',
                'type' => 'counter',
                'model' => '\App\Models\User',
                'aggregate_function' => 'count',
                'filter_field' => 'created_at',
                'filter_data' => 'alltime',
                'column_class' => 'col-lg-3',
                'background_class' => 'bg-primary',
                'icon' => 'fas fa-users'
            ]),
            'ordering' => 1
        ]);

        DashboardWidget::create([
            'name' => 'Registered Today',
            'type' => 'counter',
            'attributes' => json_encode([
                'title' => 'Registered Today',
                'type' => 'counter',
                'model' => '\App\Models\User',
                'aggregate_function' => 'count',
                'filter_field' => 'created_at',
                'filter_data' => 'days|today',
                'filter_days' => 'today',
                'column_class' => 'col-lg-3',
                'background_class' => 'bg-info',
                'icon' => 'fas fa-user-plus',
            ]),
            'ordering' => 2
        ]);

        DashboardWidget::create([
            'name' => 'Unconfirmed Users',
            'type' => 'counter',
            'attributes' => json_encode([
              'title' => 'Unconfirmed Users',
              'type' => 'counter',
              'model' => '\App\Models\User',
              'aggregate_function' => 'count',
              'filter_field' => 'email_verified_at',
              'filter_data' => 'string|null',
              'filter_value' => 'null',
              'column_class' => 'col-lg-3',
              'background_class' => 'bg-warning',
              'icon' => 'fas fa-user-alt-slash',
            ]),
            'ordering' => 3
        ]);

        DashboardWidget::create([
            'name' => 'Online Users',
            'type' => 'counter',
            'attributes' => json_encode([
              'title' => 'Online Users',
              'type' => 'counter',
              'model' => '\App\Models\User',
              'aggregate_function' => 'count',
              'filter_field' => 'last_activity',
              'filter_data' => 'minutes|5',
              'filter_minutes' => '5',
              'column_class' => 'col-lg-3',
              'background_class' => 'bg-success',
              'icon' => 'fas fa-user-clock',
            ]),
            'ordering' => 4
        ]);

        DashboardWidget::create([
            'name' => 'Latest Users',
            'type' => 'latest_records',
            'attributes' => json_encode([
              'title' => 'Latest Users',
              'type' => 'latest_records',
              'model' => '\App\Models\User',
              'column_class' => 'col-lg-6',
              'total_records' => '10',
              'view_more' => 'admin.users.index',
              'fields' => [
                'first_name' => [
                  'column_name' => 'First Name',
                  'format_type' => 'html',
                  'display_format' => '$first_name$',
                  'width' => NULL,
                ],
                'last_name' => [
                  'column_name' => 'Last Name',
                  'format_type' => 'html',
                  'display_format' => '$last_name$',
                  'width' => NULL,
                ],
                'created_at' => [
                  'column_name' => 'Created At',
                  'format_type' => 'timeago',
                  'width' => '25',
                ],
              ],
            ]),
            'ordering' => 5
        ]);

        DashboardWidget::create([
            'name' => 'Latest Activities',
            'type' => 'latest_records',
            'attributes' => json_encode([
              'title' => 'Latest Activities',
              'type' => 'latest_records',
              'model' => '\Modules\Users\Models\Activity',
              'column_class' => 'col-lg-6',
              'total_records' => '10',
              'view_more' => 'admin.activities.index',
              'fields' => [
                'description' => [
                  'column_name' => 'Activity',
                  'format_type' => 'html',
                  'display_format' => '<b>$relationship_field.first_name$ $relationship_field.last_name$</b> $description$',
                  'width' => NULL,
                  'relationship_name' => 'causer',
                  'relationship_model' => '\App\Models\User',
                  'relationship_fields' => [
                    0 => 'first_name',
                    1 => 'last_name',
                  ],
                ],
                'created_at' => [
                  'column_name' => NULL,
                  'format_type' => 'timeago',
                  'width' => '28',
                  'relationship_name' => NULL,
                  'relationship_model' => NULL,
                ],
              ],
            ]),
            'ordering' => 6
        ]);

        DashboardWidget::create([
            'name' => 'Registrations History',
            'type' => 'line',
            'attributes' => json_encode([
              'selector' => 'registrations-history-chart',
              'title' => 'Registrations History',
              'type' => 'line',
              'label' => 'Registrations History',
              'model' => '\App\Models\User',
              'group_by_field' => 'created_at',
              'group_by_period' => 'month',
              'group_by_field_format' => 'Y-m-d H:i:s',
              'group_by_column' => 'MONTH|created_at',
              'aggregate_function' => 'count',
              'filter_field' => 'created_at',
              'filter_data' => 'period|this_year',
              'filter_period' => 'this_year',
              'show_blank_data' => true,
              'column_class' => 'col-lg-12',
            ]),
            'ordering' => 7
        ]);
    }
}
