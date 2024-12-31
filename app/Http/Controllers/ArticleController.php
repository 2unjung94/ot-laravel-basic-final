<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\DeleteArticleRequest;
use App\Http\Requests\EditArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * @brief     Article CRUD 컨트롤러이다
 * @details   사용자가 작성한 글을 저장, 수정, 삭제하는 기능을 하고, 작성한 글에 대한 목록보기, 상세보기를 제공한다
 * @author    eunjeong
 * @date      2024-12-12
 * @version   1.0.0
 */
class ArticleController extends Controller
{
    public function __construct(){
        // $this->middleware('auth')->only('create');
        $this->middleware('auth')->except(['index','show']);
    
    }
    public function create(){
        return view('articles/create');
    }

    public function store(CreateArticleRequest $request){
        $input = $request->validated(); 
        $article = Article::create([
            'body' => $input['body'],   // 요청 본문에서 파싱됨. validated() 결과에 포함됨
            'user_id' => Auth::id()
        ]);

        if($request->hasFile('files')){
          $fileController = new FileController();
          // $input은 첨부파일 객체 배열은 validated 데이터에 포함되지 않기 때문에 request사용해서 가져와야 함
          $fileController->store($request->file('files'),$article->id); 
        } 
    
        return redirect()->route('articles.index');
    }

    public function index(Request $request){
        $q = $request->input('q');
        $articles = Article::with('user')
        ->withCount('comments')     // 각 글이 가지고 있는 댓글의 수
        ->withExists(['comments as recent_comments_exists'=>function($query){
            $query->where('created_at','>',Carbon::now()->subDay());
        }])
        ->when($q, function($query, $q){
          $query->where('body', 'like', "%$q%")  // 실제 사용시에는 성능에 문제가 있음. 별도의 검색엔진을 붙여서 구현하기도 함
          ->orWhereHas('user', function(Builder $query) use ($q) {
            $query->where('username', 'like', "%$q%");
          });
        })
        ->latest()
        ->paginate(10); 

        return view('articles.index'
                  , [
                     'articles'=>$articles,
                     'q'=>$q
                ]);
    }

    public function show(Article $article){
    // $article = Article::find($id);
    $article->load('comments.user');
    $article->loadCount('comments');
    return view('articles.show', ['article' => $article]);
    }

    public function edit(EditArticleRequest $request, Article $article){
        return view('articles.edit', ['article' => $article]);
    }

    public function update(UpdateArticleRequest $request, Article $article){

        $input = $request->validated();
        $article->body = $input['body'];
        $article->save();

        if($request->hasFile('files')){
          $fileController = new FileController();
          // $input은 첨부파일 객체 배열은 validated 데이터에 포함되지 않기 때문에 request사용해서 가져와야 함
          $fileController->store($request->file('files'), $article->id); 
        } 
    
        return redirect()->route('articles.index');
    }

    // 라라벨에서는 delete 대신 destroy를 사용하는게 관례임
    public function destroy(DeleteArticleRequest $request, Article $article){
        $article->files->each(function ($file){
          $filePath = public_path('images/'.$file->file_name);
          if(file_exists($filePath)){
            unlink($filePath);    // 파일 삭제
          }
          $file->delete();
        });
        $article->comments()->delete();
        $article->delete();
        return redirect()->route('articles.index');
    }
}