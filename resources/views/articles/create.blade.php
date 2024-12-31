<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('글쓰기') }}
        </h2>
    </x-slot>
    <div class="'container p-5 max-w-7xl mx-auto">     
        <form action="{{ route('articles.store') }}" method="POST" class="mt-5" enctype="multipart/form-data">
            @csrf
            <div>
              <textarea class="block w-full mb-2 rounded" name="body" required placeholder="내용입력"></textarea>
            </div>
            <!-- <input type="text" name="body" 
                    class="block w-full mb-2 rounded" value="{{ old('body') }}"> -->
            @error('body')
            <div>
              <p class="text-xs text-red-500 mb-3">{{ $message }}</p>
            </div>
            @enderror
            <input type="file" id="files" name="files[]" multiple onchange="previewFiles()">
            @error('files.*')
                <p class="text-xs text-red-500 mb-3">{{ $message }}</p>
            @enderror
            <div>
              <ul id="file-list"></ul>
            </div>
            <button class="py-1 px-3 bg-black text-white rounded text-xs">저장하기</button>
        </form>            
    </div>

    <script>
      // fileArray - 선택된 파일을 관리하는 배열.
      let fileArray = [];

      // 파일 이름 미리보기
      function previewFiles(){
        const input = document.querySelector('input[type="file"]');
        const fileList = document.querySelector('#file-list');
        fileList.innerHTML = '';
        fileArray = Array.from(input.files);  // FileList를 배열로 변환하여 저장장
        
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

      function removeFile(index){
        fileArray.splice(index, 1); // 해당 인덱스인 파일 삭제
        updateFileInput();          // 리스트 업데이트
        previewFiles();             // 리스트 출력
      }

      function updateFileInput(){
        const input = document.querySelector('input[type="file"]');
        const dataTransfer = new DataTransfer();

        fileArray.forEach(file => {
          dataTransfer.items.add(file); // 삭제된 파일을 제외한 파일 리스트 배열을 dataTrasnfer에 지정
        });

        input.files = dataTransfer.files; // 첨부파일에 반영
      }
    </script>
</x-app-layout>