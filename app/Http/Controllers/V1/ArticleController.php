<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests\V1\ArticleLikeRequest;
use App\Http\Resources\V1\ArticleResource;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articles = Article::where('user_id', $request->user()->id)->get();
        if ($articles) {
            return response()->json(
                [
                    'data' => [
                        'status code ' => 200,
                        'articles information' => $articles
                    ]
                ],
                200
            );
        }

        return response()->json([
            'data' => [
                'status code' => 404,
                'message' => 'Articles not found',
                'article informacion' => '[{}]'
            ]
        ], 404);
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
            'title' => 'required|string',
            'content' => 'required|string',
            'cover' => 'url',
        ]);

        $article = Article::create(
            [
                'SKU' =>  Str::random(8),
                'title' => $request->title,
                'content' => $request->content,
                'cover' => $request->cover,
                'user_id' => $request->user()->id
            ]
        );

        $article->save();
        return response()->json([
            'data' => [
                'status code' => 201,
                'message' => 'Successful article created',
                'article new information' => $article
            ],
        ], 201);
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
