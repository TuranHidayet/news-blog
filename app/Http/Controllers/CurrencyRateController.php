<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;

class CurrencyRateController extends Controller
{
    public function index()
    {
        $rates = CurrencyRate::all();
        return view('currency.index', compact('rates'));
    }
}

