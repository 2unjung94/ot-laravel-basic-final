<x-app-layout>
  <div class="'container p-5 mx-auto max-w-7xl">     
    <div class="border rounded p-4">
      <div class="flex justify-between mb-4">
       <p class="whitespace-pre">{{ $article->body }}</p>
      @if(Gate::any(['delete', 'update'], $article))
              <x-action-dropdown :model="$article" destroyRoute="articles.destroy" editRoute="articles.edit" />
      @endif
      </div>

      <!-- 이미지 시작 -->
      @if(!empty($article->files))
      <!-- 다운로드 div 생성-->
      @forelse($article->files as $file)
      <div>
        <p>다운로드 - <a href="{{ route('download',['filename'=>$file->file_name]) }}" class="hover:bg-sky-300">{{ $file->file_name  }}</a></p>
      </div>   
      @empty
      <div></div>
      @endforelse
      <!-- img 태그 생성-->
      @forelse($article->files as $file)  
      <img src="{{ asset('images/'.$file->file_name)}}" alt="1" style="width:50%; height:50%">
      @empty
      <div></div>
      @endforelse
      @endif
      
      <div><p><a href="{{ route('profile',['user' => $article->user->username]) }}">{{ $article->user->username }}</a></p></div>
      <p class="text-xs text-gray-500">
        <a href="{{ route('articles.show', ['article' => $article->id]) }}">
        {{ $article->created_at->diffForHumans() }}
        <span>댓글 {{ $article->comments_count }}</span>
        </a>
      </p>

    </div>
    <!-- 댓글 영역 시작 -->
    <div class="mt-3">
      <!-- 댓글 작성 폼 시작 -->
      <form action="{{route('comments.store') }}" method="POST" >
        @csrf
        <input type="hidden" name="article_id" value="{{ $article->id }}"/>
        <textarea class="block w-full mb-2 rounded" name="body" required placeholder="내용입력"></textarea>
        <div> 
          <x-primary-button>댓글 쓰기</x-primary-button>
        </div>
      </form>
        @error('body')
        <x-input-error :messages=$message/>
        @enderror
      <!-- 댓글 작성 폼 끝 --> 
             
      <!-- 댓글 목록 시작 -->
      <div class="mt-3">
        @forelse($article->comments as $comment)
        <div class="mt-4 border rounded">
          <div class="flex justify-between m-4">
            <!-- 본문내용 -->
            <p class="whitespace-pre">{{$comment->body}}</p>
                
            <!-- dropdown 메뉴-->
            <!-- delete, update 둘 중 하나 권한이 있는 경우 드롭다운 메뉴 생성 -->
            @if(Gate::any(['delete', 'update'], $comment))
              <x-action-dropdown :model="$comment" destroyRoute="comments.destroy" editRoute="" />
            @endif
          </div>
          <!-- 이름, 날짜 -->
          <div class="m-4">
            <p class="text-xs text-gray-500">{{ $comment->user->name }} {{ $comment->created_at->diffForHumans() }} 수정날짜 [{{$comment->updated_at}}]</p>
          </div>

          <!-- 숨겨진 수정 폼 -->
          <div id="edit-form-{{ $comment->id }}" class="hidden m-4">
            <form action="{{ route('comments.update', ['comment' => $comment->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <textarea name="body" class="border rounded w-full">{{ $comment->body }}</textarea>
                <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded">저장</button>
            </form>
          </div>
        </div>

        @empty
          <p>댓글이 없습니다.</p>
        @endforelse
      </div>
    </div>
  </div>
</x-app-layout>

<script>
    function toggleEditForm(event, formId) {
        event.preventDefault(); // 링크 기본 동작 막기
        const form = document.getElementById(formId);
        if (form) {
            form.classList.toggle('hidden'); // hidden 클래스 토글
        }
    }
</script>