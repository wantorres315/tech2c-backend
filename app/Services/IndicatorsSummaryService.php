<?php

namespace App\Services;

use App\Models\Emission;
use App\Data\ExcelIndicatorsData;
use App\Data\TopCompanyData;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\DataCollection;

class IndicatorsSummaryService
{
    public function summary(): ExcelIndicatorsData
    {
        $emissions = Emission::all();

        $totalCO2ByYear   = [];
        $energyValues     = [];
        $companyEmissions = [];

        foreach ($emissions as $row) {
            $company = $row->company;
            $year    = $row->year;
            $energy  = (float) $row->energy;
            $co2     = (float) $row->co2;

            if (!isset($totalCO2ByYear[$year])) {
                $totalCO2ByYear[$year] = 0;
            }
            $totalCO2ByYear[$year] += $co2;

            $energyValues[] = $energy;

            if (!isset($companyEmissions[$company])) {
                $companyEmissions[$company] = 0;
            }
            $companyEmissions[$company] += $co2;
        }

        $totalCO2ByYear = array_map(
            fn ($value) => round($value, 2),
            $totalCO2ByYear
        );

        $averageEnergy = count($energyValues)
            ? round(array_sum($energyValues) / count($energyValues), 2)
            : 0;

        $companyEmissions = array_map(
            fn ($value) => round($value, 2),
            $companyEmissions
        );

        arsort($companyEmissions);
        $top5 = array_slice($companyEmissions, 0, 5, true);

        $top5CompaniesData = new DataCollection(
            TopCompanyData::class,
            array_map(
                fn ($company, $value) => [
                    'company'   => $company,
                    'emissions' => round((float) $value, 2),
                ],
                array_keys($top5),
                $top5
            )
        );

        $raw = Emission::select(
                'year',
                'sector',
                DB::raw('SUM(co2) as total_co2')
            )
            ->groupBy('year', 'sector')
            ->orderBy('year')
            ->orderByDesc('total_co2')
            ->get();

        $emissionsBySectorPerYear = [];

        foreach ($raw as $row) {
            $year = $row->year;

            if (!isset($emissionsBySectorPerYear[$year])) {
                $emissionsBySectorPerYear[$year] = [];
            }

            $emissionsBySectorPerYear[$year][] = [
                'sector'    => $row->sector,
                'total_co2' => round((float) $row->total_co2, 2),
            ];
        }

        return new ExcelIndicatorsData(
            totalCO2PerYear: $totalCO2ByYear,
            averageEnergyConsumption: $averageEnergy,
            top5Companies: $top5CompaniesData,
            emissionsBySectorPerYear: $emissionsBySectorPerYear
        );
    }
}
