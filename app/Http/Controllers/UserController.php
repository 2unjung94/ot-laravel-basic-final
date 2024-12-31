<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
  public function email(Request $request)
  {
      $email = $request->input('email');
      $pass = rand(100000, 999999);
  
      // 수정: 로그에 전달하는 두 번째 인수를 배열로 변경
      Log::info('Receive email: ', ['email' => $email, 'pass' => $pass]);
  
      $request->session()->put('pass', $pass);
      $storedPass = $request->session()->get('pass');
  
      // dd($storedPass); // 디버깅용 코드 (개발 시에만 사용)
      Log::info('Stored pass in session:', ['storedPass' => $storedPass]);
  
      try {
          Mail::to($email)->send(new SendEmail($email, $pass));
          Log::info('Email sent successfully to: ', ['email' => $email]);
          return response()->json(['success' => true, 'message' => 'Email sent successfully']);
      } catch (\Exception $e) {
          Log::error('Email sending failed: ', ['error' => $e->getMessage()]);
          return response()->json(['success' => false, 'message' => 'Failed to send email'], 500);
      }
  }
  
  public function verify(Request $request){
    $code = $request->input('code');
    //Log::info('입력코드' .$code);
    $sessionCode = $request->session()->Get('pass');
    if($code == $sessionCode){
      return response()->json(['success'=>true, 'message' => '인증되었습니다.']);
    }else{
      return response()->json(['success'=>false, 'message' => '인증번호가 일치하지 않습니다.']);
    }
  }
}