<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyCommentRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;

/**
 * @brief     Comment CRUD 컨트롤러이다
 * @details   사용자가 작성한 댓글을 저장, 수정, 삭제하는 기능을 제공한다.
 * @author    eunjeong
 * @date      2024-12-12
 * @version   1.0.0
 */
class CommentController extends Controller
{
  public function __construct(){
    $this->middleware('auth')->only(['store']);
  }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $input = $request->validated();
        Comment::create([
            'article_id' => $input['article_id'],
            'user_id' => $request->user()->id,
            'body' => $input['body']
        ]);

        return redirect()->route('articles.show', ['article'=>$input['article_id']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $input = $request->validated();
        $comment->body = $input['body'];
        $comment->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyCommentRequest $request, Comment $comment)
    {
      $comment->delete();
      return redirect()->back();
    }
}