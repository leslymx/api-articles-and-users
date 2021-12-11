<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
                        'statusCode ' => 200,
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
                'statusCode' => 404,
                'dev' => 'NOT FOUND',
                'message' => 'Items not found',
                'items information' => '{}'
            ]
        ], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $article = Article::create(
            [
                'SKU' =>  Str::random(8),
                'title' => $request->title,
                'content' => $request->content,
                'cover' => $request->cover,
                'user_id' => $request->user()->id,
                'like' => 0
            ]
        );
        $article->save();

        return response()->json([
            'data' => [
                'statusCode' => 201,
                'dev' => 'CREATED',
                'message' => 'Successful article created',
                'new item information' => new ArticleResource($article)
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
        $userId = $request->user()->id;
        if ($article->user_id === $userId) {

            return response()->json([
                'statusCode' => 200,
                'dev' => 'OK',
                'message' => 'Item found successfully',
                'item information' => new ArticleResource($article)
            ], 200);
        }

        return response()->json([
            'statusCode' => 400,
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
        /**
         * increments the like field by one each time you send the request
         */
        $userId = $request->user()->id; // get id from token

        if ($userId === $article->user_id) {

            $article->like = $this->increaseLike($article->like);
            $article->save();

            return response()->json(
                [
                    'statusCode' => 200,
                    'dev' => 'OK',
                    'message' => 'Like increased by 1',
                    'item information' => new ArticleResource($article)
                ],
                200
            );
        }

        return response()->json([
            'statusCode' => 400,
            'dev' => 'BAD REQUEST',
            'message' => 'The article you want to modify does not belong to this user',
            'item information' => '{}'
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article, Request $request)
    {
        $userId = $request->user()->id;

        if ($article->user_id === $userId) {
            $article->delete();
            return response()->json([
                'statusCode' => 200,
                'dev' => 'OK',
                'message' => 'Item removed successfully'
            ], 200);
        }

        return response()->json([
            'statusCode' => 400,
            'dev' => 'BAD REQUEST',
            'message' => 'The item you are looking for does not belong to this user',
            'item information' => '[{}]'
        ], 400);
    }

    protected function increaseLike($article)
    {
        return $article + 1;
    }
}
