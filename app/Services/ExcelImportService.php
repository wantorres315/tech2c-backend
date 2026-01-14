<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\Emission;

class ExcelImportService
{
    public function import($file): int
    {
        $rows = Excel::toCollection(null, $file)->first();

        $grouped = [];

        foreach ($rows as $index => $row) {

            if ($index === 0) {
                continue;
            }

            $company = $row[0] ?? null;
            $year    = $row[1] ?? null;
            $sector  = $row[2] ?? null;
            $energy  = $row[3] ?? null;
            $co2     = $row[4] ?? null;

            if (!$company || !$year || !$sector || $energy === null || $co2 === null) {
                continue;
            }

            $company = trim($company);
            $year    = (int) $year;
            $sector  = trim($sector);

            $energy = $this->parseNumber($energy);
            $co2    = $this->parseNumber($co2);

            $key = $company . '|' . $year . '|' . $sector;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'company' => $company,
                    'year'    => $year,
                    'sector'  => $sector,
                    'energy'  => 0,
                    'co2'     => 0,
                ];
            }

            $grouped[$key]['energy'] += $energy;
            $grouped[$key]['co2'] += $co2;
        }

        $count = 0;

        foreach ($grouped as $data) {
            Emission::updateOrCreate(
                [
                    'company' => $data['company'],
                    'year'    => $data['year'],
                    'sector'  => $data['sector'],
                ],
                [
                    'energy' => $data['energy'],
                    'co2'    => $data['co2'],
                ]
            );

            $count++;
        }

        return $count;
    }

    private function parseNumber($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = trim($value);
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        return (float) $value;
    }
}
