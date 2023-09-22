<?php

namespace Modules\Affiliates\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Affiliates\Models\AffiliateCommissionType;

class CreateDefaultCommissionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $types = [
            [
                'name' => 'Subscription Commission',
                'alias' => 'subscription_commission',
                'active' => 1,
                'levels' => json_encode([1]),
                'conditions' => json_encode(['first_billing_only' => 1])
            ],
            [
                'name' => 'Deposit Commission',
                'alias' => 'deposit_commission',
                'active' => 1,
                'levels' => json_encode([1]),
                'conditions' => '{}'
            ]
        ];

        foreach ($types as $type) {
            AffiliateCommissionType::create($type);
        }
    }
}
