<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
      $this->middleware('guest');
    }
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): View
    {
      // 사용자 입력값 검증
      $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
      ]);

      // Google2FA 비밀 키 생성
      $google2fa = app('pragmarx.google2fa');
      $google2fa_secret = $google2fa->generateSecretKey();

      // 세션에 google2fa_secret 저장
      $request->session()->put('registration_data', $request->all() + ['google2fa_secret' => $google2fa_secret]);

      // 2단계 인증 QR 코드 생성
      $QR_Image = $google2fa->getQRCodeInline(
        config('app.name'),
        $request->email,
        $google2fa_secret
      );
      
      // google2fa.register 뷰로 QR 코드와 비밀 키 전달
      return view('google2fa.register', [
        'QR_Image' => $QR_Image,
        'secret' => $google2fa_secret
      ]);
    }

    /**
     * Complete the registration process.
     */
    public function completeRegistration(Request $request): RedirectResponse
    {
        // 세션에서 등록 데이터 가져오기
        $registration_data = $request->session()->pull('registration_data');

        // 사용자 정보 DB에 저장
        $user = User::create([
            'name' => $registration_data['name'],
            'email' => $registration_data['email'],
            'password' => Hash::make($registration_data['password']),
            'google2fa_secret' => $registration_data['google2fa_secret'],
            'username' => $registration_data['username'],
        ]);

        // 사용자 등록 이벤트 발생
        event(new Registered($user));
        
        // 로그인 처리
        Auth::login($user);

        // /home으로 이동
        Log::info('registertologinSession', ['session' => $request->session()->all()]);
        return redirect($this->redirectTo);
    }
}
