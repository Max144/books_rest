<?php

namespace App\Http\Requests\Api;

use App\Book;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
        /**
         * @var Book $book
         */
        $book = request()->route('book');
        return [
            'name' => 'max:255|unique:books,name,' . $book->id,
            'authors' => 'array',
            'authors.*' => 'integer|exists:authors,id',
            'categories' => 'array',
            'categories.*' => 'integer|exists:categories,id',
        ];
    }
}
