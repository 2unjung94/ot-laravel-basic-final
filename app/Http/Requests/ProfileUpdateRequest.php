<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @brief     프로필 수정 시 권한과 유효성 검사를 진행하는 FormRequest이다.
 * @details   프로필 수정 시 이름, 이메일, 유저네임 등록에 대한 규칙을 지정한다.
 * @author    eunjeong
 * @date      2024-12-12
 * @version   1.0.0
 */

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'username' => [
              'max:255',
              'regex:/^[A-Za-z0-9-]+$/',
              'unique:users,username'  // users 테이블의 username 기준으로 unique한지 본다  !!공백주의!!
            ],
            'postcode' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'detailAddress' => ['nullable', 'string', 'max:255']
        ];
    }
}