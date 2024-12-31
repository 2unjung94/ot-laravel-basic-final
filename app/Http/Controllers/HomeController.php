<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
      $articles = Article::with('user')
      ->withCount('comments')     // 각 글이 가지고 있는 댓글의 수
      ->withExists(['comments as recent_comments_exists'=>function($query){
          $query->where('created_at','>',Carbon::now()->subDay());
      }])
      // 조건적 where절 특정 - 조건이 만족할 때 쿼리를 추가로 적용함
      ->when(Auth::check(), function($query){ // 사용자가 로그인한 상태이면
        // 연관 관계 존재 확인 (로그인한 사용자가 팔로우하는 사람의 글)
        $query->whereHas('user', function(Builder $query){  // 이 글을 쓴 유저의 아이디가 로그인한 사용자가 팔로우하는 유저의 아이디와 같아야 하는 조건
          $query->whereIn('id', Auth::user()->followings->pluck('id')->push(Auth::id())); // pluck() - 여러 컬럼 값들중 하나를 만들어서 컬렉션으로 만드는 메소드
        });
      })  
      ->latest()
      ->paginate(10); 

      return view('articles.index'
                , [
                   'articles'=>$articles,
              ]);
    }
}
