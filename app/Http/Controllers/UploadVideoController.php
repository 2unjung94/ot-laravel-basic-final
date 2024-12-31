<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UploadVideoController extends Controller
{
  public function create(){
    $videos = Video::with('user')->get();
    return view('upload/video', ['videos'=>$videos]);
  }

  public function store(Request $request){
    $file = $request->file('file'); // 파일 받아옴

    $filePath = 'videos/';  // public 파일 저장 경로 설정
    $storagePath = public_path($filePath);  // 절대 경로 받아옴

    $fileName = $file->getClientOriginalName();   // 파일 오리지널 이름 받아옴

    $file->move($storagePath, $fileName);   // public 폴더에 파일 이름 가지고 저장

    $fullPath = $filePath . $fileName;  // 데이터베이스 파일 경로 설정

    // 데이터베이스 저장장
    Video::create([
      'title' => $fileName,
      'file_path' => $fullPath,
      'user_id' => Auth::id()
    ]);

    return redirect()->route('upload.video');
    
  }

  public function show(video $video)
  {
      $videos = Video::with('user')->get();
      $fullpath = $video->file_path; // (video테이블에 저장되어있는 동영상 주소 가져오기)         
      return view('upload.show-video', ['videos'=>$videos, 'video' => $video , 'fullpath' => $fullpath]);
  }
}