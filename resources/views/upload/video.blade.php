 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
       <div class="container">
        <div class="card mt-5 w-50">
            <div class="card-header">파일 업로드</div>
            <div class="card-body">
                <form id="uploadForm" action="{{ route('upload.videoFile') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">파일을 선택하세요.</label>
                        <input type="file" class="form-control" name="file" id="file">
                    </div>
                    <button type="submit" class="btn btn-success mt-3">업로드</button>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
             <header class="px-5 py-4">
                 <h2 class="font-semibold text-slate-800 dark:text-slate-100">전체영상<span class="text-slate-400 dark:text-slate-500 font-medium">123</span></h2>
             </header>

             <div x-data="handleSelect">
                 <!-- Table -->
                 <div class="overflow-x-auto">
                     <table class="table-auto w-full dark:text-slate-300">
                         <!-- Table header -->
                         <thead class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-900/20 border-t border-b border-slate-200 dark:border-slate-700">
                             <tr>
                                 <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                     <div class="flex items-center">
                                         <label class="inline-flex">
                                             <span class="sr-only">Select all</span>
                                             <input id="parent-checkbox" class="form-checkbox" type="checkbox" @click="toggleAll" />
                                         </label>
                                     </div>
                                 </th>
                                 <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                     <span class="sr-only">Favourite</span>
                                 </th>
                                 <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                     <div class="font-semibold text-left">제목</div>
                                 </th>
                                 <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                     <div class="font-semibold text-left"></div>
                                 </th>
                                 <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                     <div class="font-semibold text-left"></div>
                                 </th>
                                 <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                     <div class="font-semibold"> </div>
                                 </th>
                                 <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                     <div class="font-semibold text-left"></div>
                                 </th>
                                 <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                     <div class="font-semibold text-left">강사이름</div>
                                 </th>
                             </tr>
                         </thead>
                         <!-- Table body -->
                         <tbody class="text-sm divide-y divide-slate-200 dark:divide-slate-700">
                             <!-- Row -->
                             
                             @foreach($videos as $video)
                             <tr>
                                 <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                     <div class="flex items-center">
                                         <label class="inline-flex">
                                             <span class="sr-only">Select</span>
                                             <input class="table-item form-checkbox" type="checkbox" @click="uncheckParent" />
                                         </label>
                                     </div>
                                 </td>
                                 <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                     <div class="flex items-center relative">
                                         <button>
                                          ★
                                           </button>
                                     </div>
                                 </td>
                                 <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                     <div class="flex items-center">
                                         <div class="w-10 h-10 shrink-0 mr-2 sm:mr-3">
                                         <a href="{{ route('videos/show-video', ['video' => $video->id])}}" target="_blank">{{ $video->title }}</a> <!-- 동영상 URL 사용 -->
                                         </div>
                                     </div>
                                 </td>
                                 <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                     <div class="text-left"></div>
                                 </td>
                                 <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                     <div class="text-left"></div>
                                 </td>
                                 <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                     <div class="text-center"></div>
                                 </td>
                                 <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                     <div class="text-center"></div>
                                 </td>
                                 <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                     <div class="text-left font-medium text-sky-500">{{ $video->user->name }}</div>
                                 </td>
                             </tr>
                             @endforeach
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>


</body>
</html>
