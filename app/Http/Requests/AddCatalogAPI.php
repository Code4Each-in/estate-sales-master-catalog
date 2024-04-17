<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AddCatalogAPI extends FormRequest
{

   
    public function rules(): array
    {
        return [
            'author_id' => 'required',
            'wp_category_id' => 'required',
            'title' => 'required',
            'name' => 'required',
            'sku' => 'required',
            'base_price' => 'required',
            'image' => 'required',
            'content' => 'required',
            'status' => 'required',
            "publish_date" => 'required' 

          ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
