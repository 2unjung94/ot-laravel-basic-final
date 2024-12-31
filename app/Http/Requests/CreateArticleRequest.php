<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @brief     게시글 저장 시 권한과 유효성 검사를 진행하는 FormRequest이다.
 * @details   글 작성시 본문의 규칙과 업로드하는 파일에 대한 규칙을 지정한다.
 * @author    eunjeong
 * @date      2024-12-12
 * @version   1.0.0
 */

class CreateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;       // false이면 항상 권한이 없는 것. 권한체크가 필요 없는 건 true로 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body'=>[
                'required',
                'string',
                'max:255'
            ],
            'files.*'=>[
              'nullable',
              'image',
              'max:2048'
            ],

        ];
    }
    public function messages(): array{
      return [
        'files.*.image'=>'이미지 파일만 업로드할 수 있습니다.',
        'files.*.max'=>'각 파일의 크기는 2MB를 초과할 수 없습니다.'
      ];
    }
}
