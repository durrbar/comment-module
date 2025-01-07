<?php

namespace Modules\Comment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'content' => 'required|max:500',
        ];

        // Add 'parent_id' validation if it's a POST request
        if ($this->isMethod('post')) {
            $rules['parent_id'] = 'nullable|exists:comments,id';
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
