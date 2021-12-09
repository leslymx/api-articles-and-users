<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Resources\V1\ArticleResource;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ValidationException $e)
    {
        $articles = Article::where('user_id', $request->user()->id)->get();
        if ($articles) {
            return response()->json(
                [
                    'data' => [
                        'status code ' => 200,
                        'dev' => 'OK',
                        'message' => 'List of Items Obtained Successfully',
                        'items information' => $articles
                    ]
                ],
                200
            );
        }

        return response()->json([
            'data' => [
                'status code' => $e->status,
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
                'items information' => '[{}]'
            ]
        ], $e->status);
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
                'dev' => 'CREATED',
                'message' => 'Successful article created',
                'new item information' => $article
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article, Request $request)
    {
        $user = $request->user()->id;
        if ($article->user_id === $user) {

            return response()->json([
                'status code' => 200,
                'dev' => 'OK',
                'message' => 'Item found successfully',
                'item information' => new ArticleResource($article)
            ], 200);
        }

        return response()->json([
            'status code' => 400,
            'dev' => 'BAD REQUEST',
            'message' => 'The item you are looking for does not belong to this user',
            'item information' => '[{ }]'
        ], 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
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
