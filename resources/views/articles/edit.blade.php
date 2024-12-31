<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('글 수정하기') }}
        </h2>
    </x-slot>
    <div class="'container p-5 max-w-7xl mx-auto">     
        <form action="{{ route('articles.update', ['article' => $article->id]) }}" method="POST" class="mt-5" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <textarea class="block w-full mb-2 rounded" name="body" required>{{ old('body') ?? $article->body}}</textarea>
            @error('body')
                <p class="text-xs text-red-500 mb-3">{{ $message }}</p>
            @enderror

            <!-- 이전 내용 가져오기 -->
            @if(!empty($article->files))
            <!-- 첨부파일 리스트-->
              <div>
                <ul>
                @forelse($article->files as $file)
                <li data-id="{{$file->id}}">{{ $file->file_name }} <button type="button" data-id="{{ $file->id }}">x</button>
                </li>
                @empty
                <li>첨부된 파일이 없습니다.</li>
                @endforelse
                </ul>

              </div>
              <ul id="file-list">
              </ul>

            @endif
            <input type="file" id="files" name="files[]" multiple onchange="previewFiles()">
            @error('file')
                <p class="text-xs text-red-500 mb-3">{{ $message }}</p>
            @enderror
            <button class="py-1 px-3 bg-black text-white rounded text-xs">저장하기</button>
        </form>            
    </div>

    <script>
      // 기존 첨부파일 삭제
      document.querySelectorAll('button[data-id]').forEach(button => {
        button.addEventListener('click', function(event){
          const fileId = event.target.getAttribute('data-id');

          // 삭제 요청을 보낼 URL
          const url = `/delete-file/${fileId}`;

          // 비동기 요청 시작
          fetch(url, {
            method: 'DELETE',
            headers: {
              'Content-Type' : 'application/json',
              'X-CSRF-TOKEN' :document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          })
          .then(response => response.json())
          .then(data => {
            if(data.success){
                // 버튼의 부모인 li 요소를 찾아서 삭제
                const liElement = event.target.closest('li');
                if (liElement) {
                    liElement.remove();  // li 항목 삭제
                }
            } else{
              alert('파일 삭제에 실패했습니다.');
            }
          })
          .catch(error => {
            console.error('파일 삭제 오류:', error);
            alert('서버에 오류가 발생했습니다.');
          });
        });
      });

      // fileArray - 선택된 파일을 관리하는 배열.
      let fileArray = [];

      // 파일 이름 미리보기
      function previewFiles(){
        const input = document.querySelector('input[type="file"]');
        const fileList = document.querySelector('#file-list');
        fileList.innerHTML = '';
        fileArray = Array.from(input.files);  // FileList를 배열로 변환하여 저장
        
        // file 목록 생성
        fileArray.forEach((file, index)=>{
          const li = document.createElement('li');
          li.textContent = file.name;
          
          // 삭제 버튼 생성
          const deleteBtn = document.createElement('button');
          deleteBtn.textContent='x';
          deleteBtn.type='button';
          deleteBtn.className = 'text-red-500 bold';

          deleteBtn.addEventListener('click', function(){
            removeFile(index);
          });

          // 리스트 항목 생성
          li.appendChild(deleteBtn);
          fileList.appendChild(li);
        })
      }

      // 새로운 첨부파일 리스트 항목 삭제
      function removeFile(index){
        fileArray.splice(index, 1);   // 해당 인덱스 파일 삭제
        updateFileInput();            // 리스트 업데이트
        previewFiles();               // 리스트 재출력
      }

      // 새로운 첨부파일 리스트 업데이트
      function updateFileInput(){
        const input = document.querySelector('input[type="file"]');
        const dataTransfer = new DataTransfer();    // 삭제된 파일을 제외한 파일 리스트 배열을 dataTrasnfer에 지정

        fileArray.forEach(file => {
          dataTransfer.items.add(file);             // 파일들을 dataTransfer에 추가
        });

        input.files = dataTransfer.files;           // input file에 갱신
      }
    </script>
</x-app-layout>