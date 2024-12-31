<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * @brief     로그인한 사용자가 자신의 정보를 수정하는 기능을 연결하는 컨트롤러이다.
 * @details   로그인한 사용자 본인의 유저네임, 이름, 이메일, 비밀번호를 수정하는 기능을 제공한다.
 * @author    eunjeong
 * @date      2024-12-12
 * @version   1.0.0
 */

class ProfileController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }
    public function show(User $user): View
    {
        //lazyloading 로드
        $user->load("articles.user");
        $user->articles->loadCount("comments");
        $user->articles->loadExists(['comments as recent_comments_exists'=>function($query){
            $query->where('created_at','>',Carbon::now()->subDay());
        }]);
        
        return view('profile.show', ['user'=> $user]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
      $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}