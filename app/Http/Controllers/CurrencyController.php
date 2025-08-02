<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\CurrencyRate;

class CurrencyController extends Controller
{
    public function fetch()
    {
        $response = Http::get('https://v6.exchangerate-api.com/v6/74ccf18b97d2e906e89b368a/latest/azn');

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

            return response()->json(['message' => 'Valyuta yeniləndi.'], 200);
        }

        return response()->json(['error' => 'Məlumat alınmadı'], 500);
    }
}
