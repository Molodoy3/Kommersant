<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class PropertyController extends Controller
{
    public function recent() {
        return response()->json(
          Property::with(['labels', 'type'])->orderByDesc('created_at')->take(8)->get()
              ->each(function ($item) {
                  $item->description = Str::limit($item->description, 200, '...');
                  $item->address = Str::limit($item->description,25, '...');
              })
        );
    }
    public function get($id) {
        //берем картинки
        $images = Storage::disk('public')->files('properties/'.$id);
        $formattedImages = [];
        foreach ($images as $image) {
            $path = Storage::disk('public')->url($image);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if ($extension == 'webp') continue;

            $filename = pathinfo($image, PATHINFO_FILENAME);
            $urlWithoutExtension = dirname($path) . '/' . $filename;
            $formattedImages[] = [
                'path' => $urlWithoutExtension,
                'extension' => $extension
            ];
        }
        $property = Property::with(['labels', 'type'])->findOrFail($id);
        $property->images = $formattedImages;
        return response()->json(
            $property
        );
    }
}
