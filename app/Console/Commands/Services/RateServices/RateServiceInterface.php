<?php

namespace App\Console\Commands\Services\RateServices;

interface RateServiceInterface
{
    public function getExchangeRate(string $currency): ?float;
}
