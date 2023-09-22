<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Modules\AvailableCurrencies\Models\AvailableCurrency;

class CreateAvailableCurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currency = Currency::where('name', 'US Dollar')->first();

        AvailableCurrency::create([
            'name' => 'US Dollar',
            'symbol' => '$',
            'code' => 'USD',
            'exchange_rate' => 1,
            'status' => 1,
            'format' => '$1,0.00',
            'currency_id' => $currency->id
        ]);
    }
}
