<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ExcelIndicatorsData extends Data
{
    public function __construct(
        public array $totalCO2PerYear,
        public float $averageEnergyConsumption,
        /** @var DataCollection<TopCompanyData> */
        public DataCollection $top5Companies,
        public array  $emissionsBySectorPerYear
    ) {}
}
