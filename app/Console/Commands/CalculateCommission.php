<?php

namespace App\Console\Commands;

use App\Console\Commands\Services\FeeCalculationService;
use Illuminate\Console\Command;

class CalculateCommission extends Command
{
    /**
     * @var string
     */
    protected $signature = 'transaction:calculate-commissions {filePath} {--addCeiling}';

    /**
     * @var string
     */
    protected $description = '';

    public function __construct(protected readonly FeeCalculationService $feeCalculationService)
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (empty($this->argument('filePath')) || !file_exists($this->argument('filePath'))) {
            $this->error('File not found');
            return;
        }
        $ceil = $this->option('addCeiling');
        $this->info('Calculating commissions...');
        try {
            $fileData = file($this->argument('filePath'));
            foreach ($fileData as $transaction) {
                $parsedData = $this->feeCalculationService->parseRow($transaction);
                if (!$parsedData) continue;
                [$bin, $amount, $currency] = $parsedData;
                $fixedAmount = $this->feeCalculationService->convertToEuro($amount, $currency);
                $commission = $this->feeCalculationService->calculateFee($fixedAmount, $bin);
                $this->info( $ceil ? number_format($commission, 2) : $commission);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

}
