<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIssueRequest extends FormRequest
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
            "category_id" => ["required", "exists:categories,id"],
            "title" => ["required", "min:3", "max:255"],
            "description" => ["nullable", "min:3"],
            "labels" => ["required", "array", "min:1"],
            "labels.*" =>  ["string", "min:1", "max:255"]
        ];
    }
}
