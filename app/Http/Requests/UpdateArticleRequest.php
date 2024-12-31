<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @brief     글 수정 시 권한과 유효성 검사를 진행하는 FormRequest이다.
 * @details   글 수정 시 로그인한 사용자의 id와 글 작성자의 id가 일치하는 권한을 갖고 있는지, 글 내용과 첨부파일에 대한 규칙을 작성한다.
 * @author    eunjeong
 * @date      2024-12-12
 * @version   1.0.0
 */

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
        public function authorize(): bool
    {
        // 모델 조회
        //dd($this->route('article'));
        // 현재 로그인 한 사용자 조회
        // dd($this->user());

        // article 모델에 대한 update 권한이 user한테 있는지 확인하는 코드
        return $this->user()->can('update', $this->route('article'));
        
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    // 유효성 검사
    public function rules(): array
    {
        return [
            // 규칙 작성
            'body'=>[
                'required',
                'string',
                'max:255'
            ],
            'file'=>[
              'image',
              'mimes:jpeg,png,jpg,gif,svg'
            ]
        ];
    }
}
