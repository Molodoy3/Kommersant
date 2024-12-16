<?php

namespace App\Http\Controllers\Api;

use App\Data\ArticleData;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\ImageConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            Article::query()
                ->orderByDesc('created_at')
                ->paginate(30)
                ->withPath($request->path ?? 'news')
                ->through(function ($item) {
                    $item->description = Str::limit($item->description, 150, '...');

                    //у картинки убираем расширение, его берем отдельно
                    $item->image_extension = pathinfo($item->image, PATHINFO_EXTENSION);
                    $item->image = pathinfo($item->image, PATHINFO_FILENAME);

                    return $item;
                })
        );
    }
    public function get($id)
    {
        $article = Article::find($id);
        if ($article) {
            $article->image_extension = pathinfo($article->image, PATHINFO_EXTENSION);
            $article->image = pathinfo($article->image, PATHINFO_FILENAME);

            if (!$article) {
                return response()->json(['message' => 'Article not found'], 404);
            }

            return response()->json($article);
        }
        return response()->json(['message' => 'Article not found'], 404);

    }
    public function create(Request $request){
        ArticleData::validate($request->input());

        if (isset($request->allFiles()['image'])) {
            $image = $request->allFiles()['image'];
            $extension = $image->getClientOriginalExtension();
            $uniqueName = Str::uuid()->toString() . '.' . $extension;

            $path = 'articles/' . $uniqueName;
            try {
                Storage::disk('public')->putFileAs('articles', $image, $uniqueName);
                ImageConverter::convertWebp($path); //This might need error handling too.

                Article::query()->create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'image' => $uniqueName,
                ]);

                return response()->json(['message' => 'Article created'], 201);
            } catch (\Exception $e) {
                Log::error("Error creating article: " . $e->getMessage());
                return response()->json(['status' => 'error', 'errors' => ['image' => 'Failed to create article']], 500);
            }
        }
        else {
            return response()->json(['status'=>'error', 'errors' => ['image' => 'Загрузите картинку']], 422);
        }
    }
    public function update(Request $request, $id) {
        //обязательно использовать input а не all
        ArticleData::validate($request->input());

        $article = Article::query()->find($id);

        if ($article) {
            if (!Storage::disk('public')->exists('articles'))
                Storage::disk('public')->makeDirectory('articles');

            if (isset($request->allFiles()['image'])) {
                $image = $request->allFiles()['image'];
                $extension = $image->getClientOriginalExtension();
                $uniqueName = Str::uuid()->toString() . '.' . $extension; // Создаем уникальное имя

                // удаляем предыдущий файл и webp тоже
                Storage::disk('public')->delete('articles/' . basename($article->image));
                Storage::disk('public')->delete('articles/' . pathinfo(basename($article->image), PATHINFO_FILENAME) . '.webp');

                $path = 'articles/' . $uniqueName;
                Storage::disk('public')->put($path, file_get_contents($image->getRealPath()));
                ImageConverter::convertWebp($path);

                $request->image = $uniqueName;

            }
            //приходится делать так, так как просто $request->image = 'fff' нифига не меняет картинку и $request->all() все равно старую возвращает
            $article->update([
                'title' => request('title'),
                'description' => request('description'),
                'image' => $request->image ?? $article->image,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Article updated successfully']);
        }
        return response()->json(['status' => 'error', 'message' => 'Article not found'], 404);
    }
    public function delete($id) {
        $article = Article::query()->find($id);
        if ($article) {
            $article->delete();
            return response()->json(['status' => 'success', 'message' => 'Article deleted successfully']);
        }
        return response()->json(['status' => 'error', 'message' => 'Article not found'], 404);
    }
}
