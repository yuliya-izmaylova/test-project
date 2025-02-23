<?php

namespace App\Console\Commands\Services;


use App\Console\Commands\Enums\CurrencyCodes;
use App\Console\Commands\Services\CodeServices\CountryCodeBinService;
use App\Console\Commands\Services\RateServices\RateService;

class FeeCalculationService
{
    public function __construct(protected readonly CountryCodeBinService $countyCodeService, protected readonly RateService $rateService)
    {
    }

    /**
     * @param $fixedAmount
     * @param $bin
     * @return float
     * @throws \Exception
     */
    public function calculateFee($fixedAmount, $bin): float
    {
        $isEu = $this->countyCodeService->isEuCountry($bin);
        return $isEu ? $fixedAmount * 0.01 : $fixedAmount * 0.02;
    }

    /**
     * @param $amount
     * @param $currency
     * @return float|int|mixed
     * @throws \Exception
     */
    public function convertToEuro($amount, $currency): mixed
    {
        $exchangeRate = $this->rateService->getExchangeRate($currency);
        if ($currency === CurrencyCodes::EUR->value || $exchangeRate == 0) {
            return $amount;
        }
        if ($currency != CurrencyCodes::EUR->value or $exchangeRate > 0) {
            return $amount/$exchangeRate;
        }
        return 0;
    }

    /**
     * @param $dataString
     * @return array|null
     */
    public function parseRow($dataString): ?array
    {
        $parsed = json_decode($dataString, true);
        if (json_last_error() !== JSON_ERROR_NONE || count($parsed) !== 3) {
            return null;
        }
        return [$parsed['bin'], $parsed['amount'], $parsed['currency']];
    }
}
