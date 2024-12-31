<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @brief     댓글 수정 시 권한과 유효성 검사를 진행하는 FormRequest이다.
 * @details   댓글 수정 시 로그인한 사용자의 id와 댓글 작성자의 id가 일치하는 권한을 갖고 있는지, 글 내용과 첨부파일에 대한 규칙을 작성한다.
 * @author    eunjeong
 * @date      2024-12-12
 * @version   1.0.0
 */

class UpdateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
      return $this->user()->can('update', $this->route('comment'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
        'body' => ['string', 'max:255'],
        'article_id'=> 'integer'
    ];
    }
}
