<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function download($fileName){
      $filePath = public_path('images/' . $fileName);
      return response()->download($filePath);
    }
}