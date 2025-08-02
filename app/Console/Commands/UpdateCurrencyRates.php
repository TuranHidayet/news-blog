<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\CurrencyRate;

class UpdateCurrencyRates extends Command
{
    protected $signature = 'currency:update';
    protected $description = 'Update AZN currency rates to USD and TRY from exchangerate-api.com';

    public function handle()
    {
        $url = 'https://v6.exchangerate-api.com/v6/74ccf18b97d2e906e89b368a/latest/azn';

        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();

            $usdRate = $data['conversion_rates']['USD'] ?? null;
            $tryRate = $data['conversion_rates']['TRY'] ?? null;

            if ($usdRate) {
                CurrencyRate::updateOrCreate(
                    ['currency_from' => 'AZN', 'currency_to' => 'USD'],
                    ['rate' => $usdRate]
                );
            }

            if ($tryRate) {
                CurrencyRate::updateOrCreate(
                    ['currency_from' => 'AZN', 'currency_to' => 'TRY'],
                    ['rate' => $tryRate]
                );
            }

            $this->info('Currency rates updated successfully!');
        } else {
            $this->error('Failed to fetch rates from API.');
        }
    }
}
