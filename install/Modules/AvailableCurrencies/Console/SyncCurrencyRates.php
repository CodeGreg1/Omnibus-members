<?php

namespace Modules\AvailableCurrencies\Console;

use Illuminate\Console\Command;
use Modules\Cashier\Facades\Cashier;
use Modules\AvailableCurrencies\Services\ExchangeRate;
use Modules\AvailableCurrencies\Repositories\AvailableCurrenciesRepository;

class SyncCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:sync-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all available currency exchange rates from api.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ExchangeRate $exchangeApi)
    {
        $currencies = (new AvailableCurrenciesRepository)->all();
        $currencyCodes = $currencies->pluck('code');
        $rates = $exchangeApi->getAll(Cashier::currency(), $currencyCodes->toArray());
        if ($rates) {
            foreach ($rates as $code => $rate) {
                $currency = $currencies->first(function ($item) use ($code) {
                    return $item->code === $code;
                });

                if ($currency) {
                    $currency->exchange_rate = $rate;
                    $currency->save();
                }
            }
        }
    }
}
