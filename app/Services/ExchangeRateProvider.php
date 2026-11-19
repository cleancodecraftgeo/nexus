<?php

namespace App\Services;

use App\Contracts\ExchangeRateProviderInterface;
use App\Enums\Currency;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ExchangeRateProvider implements ExchangeRateProviderInterface
{
    /**
     * Create a new class instance.
     */
    public function getRate(
        Currency $from,
        Currency $to,
    ): float {

        if ($from === $to) {
            return 1.0;
        }
        $cacheKey = "exchange_rate_{$from->value}_{$to->value}";


        return Cache::remember(
            $cacheKey,
            now()->addHours(6),
            function () use ($from, $to) {

                $response = Http::get(
                    "https://api.frankfurter.dev/v2/rate/{$from->value}/{$to->value}"
                );

                $response->throw();

                return (float) $response->json('rate');
            }
        );

        // return match ("{$from->value}_{$to->value}") {
        //     'USD_GEL' => 2.70,
        //     'USD_EUR' => 0.92,
        //     'GEL_USD' => 1 / 2.70,
        //     'EUR_USD' => 1 / 0.92,
        //     default => throw new \RuntimeException(
        //         "Unsupported currency pair: {$from->value} -> {$to->value}"
        //     ),
        // };


    }
}
