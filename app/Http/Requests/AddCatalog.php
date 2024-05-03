<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCatalog extends FormRequest
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
            'content' => 'required',
            // 'name' => 'required|string|max:255',
            'category' => 'nullable',
            'sku' => 'nullable|string',
            'base_price' => 'required',
            'status' => 'required',
            'image' => 'nullable|file|mimes:jpg,png,jpeg,gif,heic,heif,hevc',
            'weight' => 'nullable',
            'color' => 'nullable',
            'sale_price' => 'nullable',
            'Brand' => 'nullable',
            'length' => 'nullable',
            'width' => 'nullable',
            'height'=> 'nullable',
        ];
    }
}
