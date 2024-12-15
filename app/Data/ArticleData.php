<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class ArticleData extends Data
{
    public function __construct(
        #[Max(255)]
        public string $title,
        #[Max(4000)]
        public string $description,
    )
    {
    }
}
