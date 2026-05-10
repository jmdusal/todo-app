<?php

namespace App\Http\Requests\Todos;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TodoStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'title' => [
                'required',
                'string',
                Rule::unique('todos')
                    ->where(fn ($query) => $query->where('user_id', $this->user_id))
                    ->ignore($this->id)
            ],
            'description' => 'nullable|string',
        ];
    }
}
