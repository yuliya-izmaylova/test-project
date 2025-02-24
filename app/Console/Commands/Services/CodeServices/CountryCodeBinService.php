<?php

namespace App\Console\Commands\Services\CodeServices;



class CountryCodeBinService implements CountyCodeServiceInterface
{
    /**
     * @var string
     */
    private string $binlistBaseURL = 'https://lookup.binlist.net/';

    public function isEuCountry(string $bin): bool
    {
        $countryCode = $this->getCountryCode($bin);
        if (empty($countryCode)) {
            throw new \Exception('Error getting country code');
        }
        $euCountries = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'];
        return in_array($countryCode, $euCountries);
    }


    public function getCountryCode($bin): ?string
    {
        $countryCode = file_get_contents($this->binlistBaseURL . $bin);
        if (!$countryCode) {
            throw new \Exception('Error getting country code');
        }
        $result = json_decode($countryCode);
        return data_get($result, 'country.alpha2');
    }
}
