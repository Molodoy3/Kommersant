<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;

class PropertyData extends Data
{
    public function __construct(
        #[Max(255)]
        public string $name,
        #[Max(2000)]
        public string $description,
        #[Numeric]
        public string $prise,
        #[Max(100)]
        public string $address,
        public int $type,
        public int $transactionType,
        public int $square,
        public int $latitude,
        public int $longitude,
        #[Max(255)]
        public string $link,
        //public string $labels,
    )
    {
    }
}
