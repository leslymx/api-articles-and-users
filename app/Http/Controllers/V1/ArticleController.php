<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

use App\Http\Requests\V1\ArticleLikeRequest;
use App\Http\Resources\V1\ArticleResource;
use Illuminate\Support\Str;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $token = $request->bearerToken();
        $id_token = Str::substr($token, 0, 1);

        $articles = Article::where('user_id', $id_token)->get();
        return response()->json($articles, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'SKU' => 'required|string|unique:articles',
            'title' => 'required|string',
            'content' => 'required|string',
            'cover' => 'required|url',
            'like' => 'required',
            'user_id' => 'required'
        ]);

        $article = Article::create(
            [
                'SKU' => $request->input('SKU'),
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'cover' => $request->input('cover'),
                'like' => $request->input('like'),
                'user_id' => $request->input('user_id')
            ]
        );

        $article->save();
        return response()->json($article, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleLikeRequest $request, Article $article)
    {
        $article->where('id', $article->id)
            ->update(
                [
                    "like" => $request->input('like'),
                ]
            );

        $article->save();

        return response()->json($article, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json([
            'message' => 'Articulo eliminado.'
        ]);
    }
}
