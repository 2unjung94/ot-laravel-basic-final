<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @brief     구독하기/구독해제 기능을 담당하는 컨트롤러러
 * @details   사용자가 다른 사용자를 구독하거나 구독을 취소하는 기능을 제공한다다
 * @author    eunjeong
 * @date      2024-12-12
 * @version   1.0.0
 */

class FollowController extends Controller
{
    public function store(User $user): RedirectResponse
    {
      Auth::user()->followings()->attach($user->id);  // 붙이다

      return back();
    }

    public function destroy(User $user): RedirectResponse
    {
      Auth::user()->followings()->detach($user->id);  // 떼어내다

      return back();
    }
}