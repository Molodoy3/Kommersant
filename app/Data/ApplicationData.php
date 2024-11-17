<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;

class ApplicationData extends Data
{
    public function __construct(
        #[Max(100)]
        public string $name,
        #[Max(20), Min(11)]
        public string $telephone,
        #[Max(500)]
        public string $comment,
        public ?int $service_id,
        public ?int $property_id,
        #[Numeric]
        #[Between(0, 999999.99)]
        public ?float $user_price,
    )
    {
    }
}
