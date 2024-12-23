<?php

namespace App\Http\Controllers\Api;

use App\Data\PropertyData;
use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\Property;
use App\Models\PropertyLabel;
use App\Models\TransactionType;
use App\Models\TypeProperty;
use App\Services\ImageConverter;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class PropertyController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function recent(): JsonResponse
    {
        return response()->json(
          Property::with(['labels', 'type', 'transactionType'])->orderByDesc('created_at')->take(8)->get()
              ->each(function ($item) {
                  $this->processProperties($item);
              })
        );
    }
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            Property::with(['labels', 'type', 'transactionType'])
                ->orderByDesc('created_at')
                ->paginate(16)
                ->withPath($request->path ?? '/properties')
                ->through(
                function ($item) {
                    return $this->processProperties($item);
                }
            )
        );
    }
    private function processProperties($item)
    {
        $item->description = Str::limit($item->description, 200);
        $item->address = Str::limit($item->description,25);

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
        return $item;
    }
    public function get($id): JsonResponse
    {
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
    public function infoByProperties(): JsonResponse
    {
        $data = [];
        $data['labels'] = Label::all();
        $data['transactionTypes'] = TransactionType::all();
        $data['types'] = TypeProperty::all();

        return response()->json($data);
    }
    public function create(Request $request): JsonResponse
    {
        $data = $request->input();
        PropertyData::validate($data);

        if ($request->allFiles() && $request->allFiles()['images']) {

            //так как названия из полученных не соответствуют, меняем
            $data['type_property_id'] = $data['type'] ?? 1;
            unset($data['type']);
            $data['transaction_type_id'] = $data['transactionType'] ?? 1;
            unset($data['transactionType']);

            //надписи изымем, чтобы не мешались
            $labels = [];
            if (isset($data['labels'])) {
                $labels = json_decode($data['labels']);
            }

            unset($data['labels']);

            $newProperty = Property::query()->create($data);

            //надписи добавляем в связующую таблицу
            foreach ($labels as $labelId) {
                PropertyLabel::query()->updateOrCreate([
                    'property_id' => $newProperty->id,
                    'label_id' => $labelId
                ]);
            }
            $this->downloadImages($request, $newProperty->id);
        } else {
            return response()->json(['status'=>'error', 'errors' => ['images' => 'Загрузите хотя бы одну картинку']], 422);
        }

        return response()->json(['message' => 'Property created'], 201);
    }
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->input();
        PropertyData::validate($data);
        $property = Property::query()->findOrFail($id);

        $data['type_property_id'] = $data['type'] ?? 1;
        unset($data['type']);
        $data['transaction_type_id'] = $data['transactionType'] ?? 1;
        unset($data['transactionType']);

        //надписи изымем, чтобы не мешались
        $labels = [];
        if (isset($data['labels'])) {
            $labels = json_decode($data['labels']);
            unset($data['labels']);
        }


        $property->update($data);

        PropertyLabel::query()->where(['property_id' => $id])->delete();
        foreach ($labels as $labelId) {
            PropertyLabel::query()->updateOrCreate([
                'property_id' => $property->id,
                'label_id' => $labelId
            ]);
        }

        $imagesForDel = json_decode($data['imagesForDel']);
        if ($imagesForDel) {
            foreach ($imagesForDel as $image) {
                // ищем все файлы с разными расширениями под именем полученного и удаляем
                $currentImages = glob(storage_path('app/public/properties/'.$id.'/'.pathinfo($image, PATHINFO_FILENAME)).'.*');
                foreach ($currentImages as $currentImage) {
                    //return str_replace(storage_path('app/public/'), '', $currentImage);
                    Storage::disk('public')->delete(str_replace(storage_path('app/public/'), '', $currentImage));
                }
            }
        }

        if ($request->allFiles() && $request->allFiles()['images']) {
            $this->downloadImages($request, $id);
        }

        return response()->json(['message' => 'Property updated', 'property' => $property]);
    }
    public function delete($id): JsonResponse
    {
        $property = Property::query()->findOrFail($id);
        if ($property) {
            $property->delete();
            return response()->json(['status' => 'success', 'message' => 'Property has been deleted']);
        }
        return response()->json(['status' => 'error', 'message' => 'Property not found'], 404);
    }
    private function downloadImages($request, $id): void
    {
        foreach ($request->allFiles()['images'] as $image) {
            $extension = $image->getClientOriginalExtension();
            $uniqueName = Str::uuid()->toString() . '.' . $extension;
            $path = "properties/$id/$uniqueName";
            try {
                Storage::disk('public')->putFileAs("properties/$id", $image, $uniqueName);
                ImageConverter::convertWebp($path);
            } catch (Exception $e) {
                Log::error("Error creating article: " . $e->getMessage());
                response()->json(['status' => 'error', 'errors' => ['image' => 'Failed to create property']], 500);
                return;
            }
        }
    }
}
