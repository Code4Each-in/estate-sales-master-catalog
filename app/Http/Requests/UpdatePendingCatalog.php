<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePendingCatalog extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable',
            // 'name' => 'required|string|max:255',
            'sku' => 'nullable|string',
            'base_price' => 'required',
            'status' => 'required',
            'image' => 'nullable|file|mimes:jpg,png,jpeg,gif,heic,heif,hevc',
        ];
    }
}
