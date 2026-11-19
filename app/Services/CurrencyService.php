<?php

namespace App\Services;

use App\Contracts\ExchangeRateProviderInterface;
use App\Enums\Currency;

class CurrencyService
{

    /**
     * Create a new class instance.
     */
    public function __construct(
        private ExchangeRateProviderInterface $rateProvider,
    ) {}

    public function getRate(
        Currency $from,
        Currency $to,
    ): float {
        if ($from === $to) {
            return 1.0;
        }

        return $this->rateProvider->getRate($from, $to);
    }

    public function convert(
        float $amount,
        Currency $from,
        Currency $to,
    ): float {
        if ($from === $to) {
            return $amount;
        }
        $rate = $this->getRate($from, $to);
        return round($amount * $rate, 2);
    }
}
