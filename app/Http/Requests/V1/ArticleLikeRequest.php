<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ArticleLikeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'nullable',
            'content' => 'nullable',
            'like' => 'required', 'number',
            'SKU' => 'nullable',
            'user_id' => 'nullable',
            'cover' => 'nullable'
        ];
    }
}
