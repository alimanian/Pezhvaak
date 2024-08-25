<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\BaseFormRequest;

class StorePostRequest extends BaseFormRequest
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
        return array_merge(parent::rules(), [
            'content' => 'required|string',
            'attachments' => 'sometimes|array',
            'attachments.*' => 'file|mimes:jpeg,png,mp4|max:10240',
        ]);
    }
}
