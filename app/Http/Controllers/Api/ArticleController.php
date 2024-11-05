<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\ImageConverter;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        //::compression('articles/image.jpeg');
        //ImageConverter::convertWebp('articles/image.jpeg');


        return response()->json(
            Article::query()
                ->orderByDesc('created_at')
                ->paginate(5)
                ->withPath('/news')
                ->through(function ($item) {
                    $item->description = Str::limit($item->description, 150, '...');

                    //у картинки убираем расширение, его берем отдельно
                    $item->image = pathinfo($item->image, PATHINFO_FILENAME);
                    $item->image_extension = pathinfo($item->image, PATHINFO_EXTENSION);

                    return $item;
                })
        );
    }
    public function get($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article);
    }
}
