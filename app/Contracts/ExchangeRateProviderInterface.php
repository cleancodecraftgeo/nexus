<?php

namespace App\Contracts;

use App\Enums\Currency;

interface ExchangeRateProviderInterface
{
    public function getRate(Currency $from, Currency $to): float;
}
