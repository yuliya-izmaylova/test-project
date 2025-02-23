<?php

namespace App\Console\Commands\Services\RateServices;



use App\Console\Commands\Enums\CurrencyCodes;

class RateService implements RateServiceInterface
{
    /**
     * @var string
     */
    private string $exchangeratesapiURL = 'https://api.apilayer.com/exchangerates_data/';

    /**
     * @param string $currency
     * @return float
     * @throws \Exception
     */
    public function getExchangeRate(string $currency): float
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->exchangeratesapiURL . "latest?symbols=" . $currency . "&base=" . CurrencyCodes::EUR->value,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: " . config('services.exchangeratesapi.key', 'DZCUwDmQqpyhTCYTg9QkyRTOFXxdaQ6R')
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        if (!$response['success']) {
            throw new \Exception('Error getting exchange rate');
        }

        return data_get($response ,'rates.' . $currency, 0);
    }
}
