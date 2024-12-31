<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @brief     댓글 저장 시 권한과 유효성 검사를 진행하는 FormRequest이다.
 * @details   댓글 저장 시 작성 내용에 대한 규칙을 작성한다.
 * @author    eunjeong
 * @date      2024-12-12
 * @version   1.0.0
 */

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
