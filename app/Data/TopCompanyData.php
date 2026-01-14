<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class TopCompanyData extends Data
{
    public function __construct(
        public string $company,
        public float $emissions,
    ) {}
}
