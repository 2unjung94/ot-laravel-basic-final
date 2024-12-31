<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function store($files, $articleId){
    foreach($files as $file){
        $fileName = time().'_'.$file->getClientOriginalName();
        $filePath = public_path('images');
        $dbFilePath = 'images/'.$fileName;
        $file->move($filePath, $fileName);

        File::create([
          'article_id'=>$articleId,
          'file_name'=>$fileName,
          'file_path'=>$dbFilePath
        ]);
      }
    }

    public function destroy($fileId){
      $file = File::find($fileId);
      $filePath = public_path('images/'.$file->file_name);

      if($file){
        unlink($filePath);    // 파일 삭제
        $file->delete();                // db 삭제
        return response()->json(['success'=>true]);   // 비동기 통신을 위한 json 응답
      }
      return response()->json(['success'=>false]);    // 비동기 통신을 위한 json 응답
    }
}