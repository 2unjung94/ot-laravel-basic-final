<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @brief     댓글 삭제 시 권한과 유효성 검사를 진행하는 FormRequest이다.
 * @details   댓글 삭제 시 현재 로그인한 사용자의 id와 글을 작성한 사람의 id가 같은 권한을 가지고 있는지 확인한다.
 * @author    eunjeong
 * @date      2024-12-12
 * @version   1.0.0
 */

class DestroyCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('delete', $this->route('comment'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
