<?php

namespace App\Services;

use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;

class ImageConverter
{
    public static function compression($imagePath)
    {
        $imagePath = storage_path('app/public/' . $imagePath);
        $image = Image::read($imagePath);
        $image->encode(new AutoEncoder(70))->save($imagePath);
    }
    public static function convertWebp($imagePath) {
        $imagePath = storage_path('app/public/' . $imagePath);
        $image = Image::read($imagePath);
        $webpPath = pathinfo($imagePath, PATHINFO_DIRNAME) . '/' . pathinfo($imagePath, PATHINFO_FILENAME) . '.webp';
        $image->encode(new WebpEncoder())->save($webpPath);
    }
}
