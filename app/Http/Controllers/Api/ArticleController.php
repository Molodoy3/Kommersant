<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        return response()->json(
            Article::query()
                ->orderByDesc('created_at')
                ->paginate(5)
                ->withPath('/news')
                ->through(function ($item) {
                    $item->description = Str::limit($item->description, 150, '...');
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
