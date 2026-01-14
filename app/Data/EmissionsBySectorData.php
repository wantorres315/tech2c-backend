<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class EmissionsBySectorData extends Data
{
    public function __construct(
        public string $sector,
        public float $emissions,
    ) {}
}
