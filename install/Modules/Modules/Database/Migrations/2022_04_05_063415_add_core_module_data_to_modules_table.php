<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoreModuleDataToModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $coreModules = [
            [
                'name' => 'Auth',
                'table_name' => 'auth',
                'attributes' => '{}',
                'is_core' => 1
            ],
            [
                'name' => 'Dashboard',
                'table_name' => 'dashboard',
                'attributes' => '{}',
                'is_core' => 1
            ],
            [
                'name' => 'EmailTemplates',
                'table_name' => 'email_templates',
                'attributes' => '{}',
                'is_core' => 1
            ],
            [
                'name' => 'Languages',
                'table_name' => 'languages',
                'attributes' => '{}',
                'is_core' => 1
            ],
            [
                'name' => 'Menus',
                'table_name' => 'menus',
                'attributes' => '{}',
                'is_core' => 1
            ],
            [
                'name' => 'Modules',
                'table_name' => 'modules',
                'attributes' => '{}',
                'is_core' => 1
            ],
            [
                'name' => 'Permissions',
                'table_name' => 'permissions',
                'attributes' => '{}',
                'is_core' => 1
            ],
            [
                'name' => 'Profile',
                'table_name' => 'profiles',
                'attributes' => '{}',
                'is_core' => 1
            ],
            [
                'name' => 'Roles',
                'table_name' => 'roles',
                'attributes' => '{}',
                'is_core' => 1
            ],
            [
                'name' => 'Users',
                'table_name' => 'users',
                'attributes' => '{}',
                'is_core' => 1
            ]
        ];

        foreach($coreModules as $module) {

            $check = DB::table('modules')
                ->where('name', $module['name'])
                ->first();

            if(is_null($check)) {
                DB::table('modules')->insert($module);
            }
            
        }

        
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

    protected function localesAttributes() 
    {
        return '{"id":"30","module_name":"Locales","model_name":"Locale","menu_title":{"name":"Locales","icon":"fas fa-map-marker-alt"},"parent_menu":"20","roles":["1"],"included":{"soft_deletes":"on","create_form":"on","edit_form":"on","show_page":"on","delete_action":"on","multi_delete_action":"on"},"models":"[{\"users\":{\"namespace\":\"\\\\App\\\\Models\\\\User\",\"model_name\":\"User\"}},{\"barangays\":{\"namespace\":\"\\\\Modules\\\\Barangays\\\\Models\\\\Barangay\",\"model_name\":\"Barangay\"},\"addresses\":{\"namespace\":\"\\\\Modules\\\\Base\\\\Models\\\\Address\",\"model_name\":\"Address\"},\"countries\":{\"namespace\":\"\\\\Modules\\\\Base\\\\Models\\\\Country\",\"model_name\":\"Country\"},\"currencies\":{\"namespace\":\"\\\\Modules\\\\Base\\\\Models\\\\Currency\",\"model_name\":\"Currency\"},\"languages\":{\"namespace\":\"\\\\Modules\\\\Base\\\\Models\\\\Language\",\"model_name\":\"Language\"},\"coupons\":{\"namespace\":\"\\\\Modules\\\\Billings\\\\Models\\\\Coupon\",\"model_name\":\"Coupon\"},\"coupon_amounts\":{\"namespace\":\"\\\\Modules\\\\Billings\\\\Models\\\\CouponAmount\",\"model_name\":\"CouponAmount\"},\"coupon_codes\":{\"namespace\":\"\\\\Modules\\\\Billings\\\\Models\\\\CouponCode\",\"model_name\":\"CouponCode\"},\"payment_gateways\":{\"namespace\":\"\\\\Modules\\\\Billings\\\\Models\\\\PaymentGateway\",\"model_name\":\"PaymentGateway\"},\"payment_gateway_credentials\":{\"namespace\":\"\\\\Modules\\\\Billings\\\\Models\\\\PaymentGatewayCredential\",\"model_name\":\"PaymentGatewayCredential\"},\"payment_gateway_webhooks\":{\"namespace\":\"\\\\Modules\\\\Billings\\\\Models\\\\PaymentGatewayWebhook\",\"model_name\":\"PaymentGatewayWebhook\"},\"plan_redeem_limits\":{\"namespace\":\"\\\\Modules\\\\Billings\\\\Models\\\\PlanRedeemLimit\",\"model_name\":\"PlanRedeemLimit\"},\"blogs\":{\"namespace\":\"\\\\Modules\\\\Blogs\\\\Models\\\\Blog\",\"model_name\":\"Blog\"},\"categories\":{\"namespace\":\"\\\\Modules\\\\Categories\\\\Models\\\\Category\",\"model_name\":\"Category\"},\"clients\":{\"namespace\":\"\\\\Modules\\\\Clients\\\\Models\\\\Client\",\"model_name\":\"Client\"},\"contents\":{\"namespace\":\"\\\\Modules\\\\Contents\\\\Models\\\\Content\",\"model_name\":\"Content\"},\"email_templates\":{\"namespace\":\"\\\\Modules\\\\EmailTemplates\\\\Models\\\\EmailTemplate\",\"model_name\":\"EmailTemplate\"},\"employees\":{\"namespace\":\"\\\\Modules\\\\Employees\\\\Models\\\\Employee\",\"model_name\":\"Employee\"},\"files\":{\"namespace\":\"\\\\Modules\\\\Files\\\\Models\\\\File\",\"model_name\":\"File\"},\"locales\":{\"namespace\":\"\\\\Modules\\\\Locales\\\\Models\\\\Locale\",\"model_name\":\"Locale\"},\"menus\":{\"namespace\":\"\\\\Modules\\\\Menus\\\\Models\\\\Menu\",\"model_name\":\"Menu\"},\"menu_links\":{\"namespace\":\"\\\\Modules\\\\Menus\\\\Models\\\\MenuLink\",\"model_name\":\"MenuLink\"},\"menu_roles\":{\"namespace\":\"\\\\Modules\\\\Menus\\\\Models\\\\MenuRole\",\"model_name\":\"MenuRole\"},\"modules\":{\"namespace\":\"\\\\Modules\\\\Modules\\\\Models\\\\Module\",\"model_name\":\"Module\"},\"module_relations\":{\"namespace\":\"\\\\Modules\\\\Modules\\\\Models\\\\ModuleRelation\",\"model_name\":\"ModuleRelation\"},\"gateway_packages\":{\"namespace\":\"\\\\Modules\\\\Packages\\\\Models\\\\GatewayPackage\",\"model_name\":\"GatewayPackage\"},\"gateway_package_prices\":{\"namespace\":\"\\\\Modules\\\\Packages\\\\Models\\\\GatewayPackagePrice\",\"model_name\":\"GatewayPackagePrice\"},\"packages\":{\"namespace\":\"\\\\Modules\\\\Packages\\\\Models\\\\Package\",\"model_name\":\"Package\"},\"package_features\":{\"namespace\":\"\\\\Modules\\\\Packages\\\\Models\\\\PackageFeature\",\"model_name\":\"PackageFeature\"},\"package_prices\":{\"namespace\":\"\\\\Modules\\\\Packages\\\\Models\\\\PackagePrice\",\"model_name\":\"PackagePrice\"},\"package_terms\":{\"namespace\":\"\\\\Modules\\\\Packages\\\\Models\\\\PackageTerm\",\"model_name\":\"PackageTerm\"},\"companies\":{\"namespace\":\"\\\\Modules\\\\Profile\\\\Models\\\\Company\",\"model_name\":\"Company\"},\"profile_password_changes\":{\"namespace\":\"\\\\Modules\\\\Profile\\\\Models\\\\ProfilePasswordChange\",\"model_name\":\"ProfilePasswordChange\"},\"sessions\":{\"namespace\":\"\\\\Modules\\\\Profile\\\\Models\\\\Session\",\"model_name\":\"Session\"},\"session_locations\":{\"namespace\":\"\\\\Modules\\\\Profile\\\\Models\\\\SessionLocation\",\"model_name\":\"SessionLocation\"},\"social_logins\":{\"namespace\":\"\\\\Modules\\\\Profile\\\\Models\\\\SocialLogin\",\"model_name\":\"SocialLogin\"},\"settings\":{\"namespace\":\"\\\\Modules\\\\Settings\\\\Models\\\\Setting\",\"model_name\":\"Setting\"},\"staff\":{\"namespace\":\"\\\\Modules\\\\Staffs\\\\Models\\\\Staff\",\"model_name\":\"Staff\"},\"subscriptions\":{\"namespace\":\"\\\\Modules\\\\Subscriptions\\\\Models\\\\Subscription\",\"model_name\":\"Subscription\"},\"tags\":{\"namespace\":\"\\\\Modules\\\\Tags\\\\Models\\\\Tag\",\"model_name\":\"Tag\"},\"testers\":{\"namespace\":\"\\\\Modules\\\\Tester\\\\Models\\\\Tester\",\"model_name\":\"Tester\"},\"testings\":{\"namespace\":\"\\\\Modules\\\\Testing\\\\Models\\\\Testing\",\"model_name\":\"Testing\"},\"to_manies\":{\"namespace\":\"\\\\Modules\\\\ToMany\\\\Models\\\\ToMany\",\"model_name\":\"ToMany\"},\"uploads\":{\"namespace\":\"\\\\Modules\\\\Uploads\\\\Models\\\\Upload\",\"model_name\":\"Upload\"},\"witnesses\":{\"namespace\":\"\\\\Modules\\\\Witness\\\\Models\\\\Witness\",\"model_name\":\"Witness\"}}]","fields":{"id":"[{\"name\":\"field_id\",\"value\":\"000000001_id\"},{\"name\":\"field_type\",\"value\":\"text\"},{\"name\":\"field_visual_title\",\"value\":\"Id\"},{\"name\":\"field_database_column\",\"value\":\"id\"},{\"name\":\"field_validation\",\"value\":\"optional\"},{\"name\":\"field_tooltip\",\"value\":\"\"},{\"name\":\"in_list\",\"value\":\"on\"},{\"name\":\"in_create\",\"value\":\"off\"},{\"name\":\"in_edit\",\"value\":\"off\"},{\"name\":\"in_show\",\"value\":\"on\"},{\"name\":\"is_sortable\",\"value\":\"off\"}]","title":"[{\"name\":\"field_type\",\"value\":\"text\"},{\"name\":\"field_visual_title\",\"value\":\"Title\"},{\"name\":\"field_database_column\",\"value\":\"title\"},{\"name\":\"field_validation\",\"value\":\"required\"},{\"name\":\"field_tooltip\",\"value\":\"\"},{\"name\":\"in_list\",\"value\":\"on\"},{\"name\":\"in_create\",\"value\":\"on\"},{\"name\":\"in_edit\",\"value\":\"on\"},{\"name\":\"in_show\",\"value\":\"on\"},{\"name\":\"is_sortable\",\"value\":\"on\"},{\"name\":\"min_length\",\"value\":\"\"},{\"name\":\"max_length\",\"value\":\"\"},{\"name\":\"default_value\",\"value\":\"\"},{\"name\":\"field_id\",\"value\":\"7737974757_title\"}]","code":"[{\"name\":\"field_id\",\"value\":\"7127836159_code\"},{\"name\":\"field_type\",\"value\":\"text\"},{\"name\":\"field_visual_title\",\"value\":\"Code\"},{\"name\":\"field_database_column\",\"value\":\"code\"},{\"name\":\"field_validation\",\"value\":\"required_unique\"},{\"name\":\"field_tooltip\",\"value\":\"https:\/\/en.wikipedia.org\/wiki\/List_of_ISO_639-1_codes\"},{\"name\":\"in_list\",\"value\":\"on\"},{\"name\":\"in_create\",\"value\":\"on\"},{\"name\":\"in_edit\",\"value\":\"on\"},{\"name\":\"in_show\",\"value\":\"on\"},{\"name\":\"is_sortable\",\"value\":\"on\"},{\"name\":\"min_length\",\"value\":\"\"},{\"name\":\"max_length\",\"value\":\"2\"},{\"name\":\"default_value\",\"value\":\"\"}]","direction":"[{\"name\":\"field_id\",\"value\":\"2755121248_direction\"},{\"name\":\"field_type\",\"value\":\"select\"},{\"name\":\"field_visual_title\",\"value\":\"Direction\"},{\"name\":\"field_database_column\",\"value\":\"direction\"},{\"name\":\"field_validation\",\"value\":\"required\"},{\"name\":\"field_tooltip\",\"value\":\"\"},{\"name\":\"in_list\",\"value\":\"on\"},{\"name\":\"in_create\",\"value\":\"on\"},{\"name\":\"in_edit\",\"value\":\"on\"},{\"name\":\"in_show\",\"value\":\"on\"},{\"name\":\"is_sortable\",\"value\":\"on\"},{\"name\":\"choices_database_value[0]\",\"value\":\"ltr\"},{\"name\":\"choices_label_text[0]\",\"value\":\"Left To Right\"},{\"name\":\"choices_database_value[1]\",\"value\":\"rtl\"},{\"name\":\"choices_label_text[1]\",\"value\":\"Right To Left\"}]","flag":"[{\"name\":\"field_type\",\"value\":\"belongsToRelationship\"},{\"name\":\"field_visual_title\",\"value\":\"Flag\"},{\"name\":\"field_database_column\",\"value\":\"flag\"},{\"name\":\"field_validation\",\"value\":\"required\"},{\"name\":\"field_tooltip\",\"value\":\"\"},{\"name\":\"in_create\",\"value\":\"on\"},{\"name\":\"in_edit\",\"value\":\"on\"},{\"name\":\"related_model\",\"value\":\"\\\\Modules\\\\Base\\\\Models\\\\Country\"},{\"name\":\"field_show\",\"value\":\"name\"},{\"name\":\"field_id\",\"value\":\"5080267726_flag\"}]","active":"[{\"name\":\"field_id\",\"value\":\"5230583589_active\"},{\"name\":\"field_type\",\"value\":\"checkbox\"},{\"name\":\"field_visual_title\",\"value\":\"Active\"},{\"name\":\"field_database_column\",\"value\":\"active\"},{\"name\":\"field_validation\",\"value\":\"optional\"},{\"name\":\"field_tooltip\",\"value\":\"\"},{\"name\":\"in_list\",\"value\":\"on\"},{\"name\":\"in_create\",\"value\":\"on\"},{\"name\":\"in_edit\",\"value\":\"on\"},{\"name\":\"in_show\",\"value\":\"on\"},{\"name\":\"default_value\",\"value\":\"checked\"}]","created_at":"[{\"name\":\"field_id\",\"value\":\"000000002_created_at\"},{\"name\":\"field_type\",\"value\":\"text\"},{\"name\":\"field_visual_title\",\"value\":\"Created at\"},{\"name\":\"field_database_column\",\"value\":\"created_at\"},{\"name\":\"field_validation\",\"value\":\"optional\"},{\"name\":\"field_tooltip\",\"value\":\"\"},{\"name\":\"in_list\",\"value\":\"off\"},{\"name\":\"in_create\",\"value\":\"off\"},{\"name\":\"in_edit\",\"value\":\"off\"},{\"name\":\"in_show\",\"value\":\"off\"},{\"name\":\"is_sortable\",\"value\":\"off\"}]","updated_at":"[{\"name\":\"field_id\",\"value\":\"000000003_updated_at\"},{\"name\":\"field_type\",\"value\":\"text\"},{\"name\":\"field_visual_title\",\"value\":\"Updated at\"},{\"name\":\"field_database_column\",\"value\":\"updated_at\"},{\"name\":\"field_validation\",\"value\":\"optional\"},{\"name\":\"field_tooltip\",\"value\":\"\"},{\"name\":\"in_list\",\"value\":\"off\"},{\"name\":\"in_create\",\"value\":\"off\"},{\"name\":\"in_edit\",\"value\":\"off\"},{\"name\":\"in_show\",\"value\":\"off\"},{\"name\":\"is_sortable\",\"value\":\"off\"}]","deleted_at":"[{\"name\":\"field_id\",\"value\":\"000000003_deleted_at\"},{\"name\":\"field_type\",\"value\":\"text\"},{\"name\":\"field_visual_title\",\"value\":\"Deleted at\"},{\"name\":\"field_database_column\",\"value\":\"deleted_at\"},{\"name\":\"field_validation\",\"value\":\"optional\"},{\"name\":\"field_tooltip\",\"value\":\"\"},{\"name\":\"in_list\",\"value\":\"off\"},{\"name\":\"in_create\",\"value\":\"off\"},{\"name\":\"in_edit\",\"value\":\"off\"},{\"name\":\"in_show\",\"value\":\"off\"},{\"name\":\"is_sortable\",\"value\":\"off\"}]"},"entries_per_page":"10","order_by_column":"id","order_by_value":"desc","table_names":["locales"],"route_name":"locales"}';
    }
}
