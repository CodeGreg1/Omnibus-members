<?php

namespace Modules\Dashboard\Services;

class Widgets {

    /**
     * List of available widgets to display in the dashboard
     * 
     * @var array $settings
     */
    protected $settings = [
        [
            'title' => 'Total Users',
            'type' => 'counter',
            'model' => '\App\Models\User',
            'aggregate_function' => 'count',
            'filter_field' => 'created_at',
            'filter_data' => 'alltime',
            'column_class' => 'col-lg-3',
            'background_class' => 'bg-primary',
            'icon' => 'fas fa-users',
        ],
        [
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
        ],
        [
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
        ],
        [
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
        ],
        [
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
                    'width' => '',
                ],
                'last_name' => [
                    'column_name' => 'Last Name',
                    'format_type' => 'html',
                    'display_format' => '$last_name$',
                    'width' => '',
                ],
                'created_at' => [
                    'column_name' => 'Created At',
                    'format_type' => 'timeago',
                    'width' => '25',
                ],
            ],
        ],
        [
            'title' => 'Latest Activities',
            'type' => 'latest_records',
            'model' => '\Modules\Users\Models\Activity',
            'column_class' => 'col-lg-6',
            'total_records' => '10',
            'filter_field' => 'causer_type',
            'filter_data' => 'string|not_null',
            'filter_value' => 'not_null',
            'view_more' => 'admin.activities.index',
            'fields' => [
                'description' => [
                    'column_name' => 'Activity',
                    'format_type' => 'html',
                    'display_format' => '<b>$relationship_field.first_name$ $relationship_field.last_name$</b> $description$',
                    'width' => '',
                    'relationship_name' => 'causer',
                    'relationship_model' => '\App\Models\User',
                    'relationship_fields' => [
                        '0' => 'first_name',
                        '1' => 'last_name',
                    ],
                ],
                'created_at' => [
                    'column_name' => '',
                    'format_type' => 'timeago',
                    'width' => '28',
                    'relationship_name' => '',
                    'relationship_model' => '',
                ],
            ],
        ],
        [
            'selector' => '7-registrations-history-chart',
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
            'show_blank_data' => '1',
            'column_class' => 'col-lg-12',
        ],

    ];

    /**
     * Get the available widgets with array
     * 
     * @return array
     */
    public function get() 
    {
    	return $this->settings;
    }

}