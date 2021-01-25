<?php

namespace App\Http\Requests\Api;

use App\Support\Response\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CommentRequest extends FormRequest
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
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|max:5000'
        ];
    }

    public function attributes()
    {
        return [
            'post_id' => 'Post',
            'comment' => 'Yorum'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new Response(
            422,
            'İstek datası doğrulamadan geçemedi!',
            (new ValidationException($validator))->errors()
        );

        throw new HttpResponseException($response->respond());
    }
}
