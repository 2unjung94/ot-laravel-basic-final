<div class="background-white border rounded mb-3 p-3">
  <div class="flex justify-between m-4">
    <p class="whitespace-pre"><a href="{{ route('articles.show', ['article' => $article->id]) }}">{{ $article->body }}</a></p>
    
    @if(Gate::any(['delete', 'update'], $article))
      <x-action-dropdown :model="$article" destroyRoute="articles.destroy" editRoute="articles.edit" />
    @endif
  </div>
  <div class="m-4">
    <p><a href="{{ route('profile',['user' => $article->user->username]) }}">{{ $article->user->username }}</a></p>     
  </div>
  <div class="flex justify-between m-4">
    <p class="text-xs text-gray-500"><a href="{{ route('articles.show', ['article' => $article->id]) }}">{{ $article->created_at->diffForHumans() }} 작성 
      <span>댓글 {{ $article->comments_count }} @if($article->recent_comments_exists) (new) @endif</span></a>
    </p>
    
  </div>
</div>