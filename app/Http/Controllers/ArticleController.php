<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Cache;


class ArticleController extends Controller
{   
    public function index(Request $request)
    {   
        $value = Cache::get('spy_index_call');

        // It returns list of articles with embedded photo and user data
        $user_id = $request->query('user_id');

        if ($user_id) {
            return Article::with(['photo', 'user'])->where('user_id', $user_id)->get();
        }

        return Article::with(['photo', 'user'])->get();
    }

    public function show($id)
    {   
        $value = Cache::get('spy_show_call');

        return Article::with(['photo', 'user'])->get()->find($id);
    }

    public function store(Request $request)
    {   
        $value = Cache::get('spy_store_call');

        $article = Article::create($request->all());

        return response()->json($article, 201);
    }

    public function update(Request $request, Article $article)
    {   
        $article->update($request->all());

        return response()->json($article, 200);
    }

    public function delete(Article $article)
    {
        $article->delete();

        return response()->json(null, 204);
    }

}
