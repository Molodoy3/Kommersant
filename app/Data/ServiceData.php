<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;

class ServiceData extends Data
{
    public function __construct(
        #[Max(255)]
        public string $title,
        #[Max(1000)]
        public string $description,
        #[Numeric]
        public int $price,
        #[Numeric]
        public int $category_id,
    )
    {
    }
}
