<?php

namespace App\Http\Controllers;

use App\Exports\BoardsExport;
use Illuminate\Http\Request;
use Vtiful\Kernel\Excel;

class BoardController extends Controller
{
    public function excel(){
      return Excel::download(new BoardsExport(), 'products.xlsx');
    }
}
