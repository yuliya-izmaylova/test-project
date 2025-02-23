<?php

namespace App\Console\Commands\Services\CodeServices;

interface CountyCodeServiceInterface
{
    public function isEuCountry(string $bin): bool;
    public function getCountryCode(string $bin): string;
}
