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
          Property::with(['labels', 'type', 'transactionType'])->orderByDesc('created_at')->take(8)->get()
              ->each(function ($item) {
                  $item->description = Str::limit($item->description, 200, '...');
                  $item->address = Str::limit($item->description,25, '...');

                  // Получаем все файлы в директории properties/{id}
                  $files = Storage::disk('public')->files('properties/' . $item->id);
                  // Если файлы существуют, берем первый файл
                  if (!empty($files)) {
                      // Сортируем файлы по имени
                      sort($files);
                      // Берем первый файл (самый первый по алфавиту)
                      $firstFile = $files[0];
                      // Получаем путь без расширения и само расширение
                      $pathWithoutExtension = pathinfo($firstFile, PATHINFO_FILENAME);
                      $extension = pathinfo($firstFile, PATHINFO_EXTENSION);
                      // Устанавливаем значения
                      $item->image = Storage::disk('public')->url('properties/' . $item->id . '/' . $pathWithoutExtension); // Путь без расширения в виде URL
                      $item->image_extension = $extension; // Расширение
                  } else {
                      $item->imagePath = null; // Или какое-то значение по умолчанию
                      $item->imageExtension = null;
                  }
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
        $property = Property::with(['labels', 'type', 'transactionType'])->findOrFail($id);
        $property->images = $formattedImages;
        return response()->json(
            $property
        );
    }
}
