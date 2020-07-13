<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class IndexBooksRequest extends FormRequest
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
            'book_name' => 'string',
            'authors' => 'array',
            'authors.*' => 'integer|exists:authors,id',
            'categories' => 'array',
            'categories.*' => 'integer|exists:categories,id',
        ];
    }
}
