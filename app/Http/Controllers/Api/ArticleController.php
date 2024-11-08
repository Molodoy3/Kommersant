<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {


        return response()->json(
            Article::query()
                ->orderByDesc('created_at')
                ->paginate(5)
                ->withPath('/news')
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

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article);
    }
}
