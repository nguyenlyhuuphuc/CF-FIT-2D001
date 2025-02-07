<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryStoreRequest extends FormRequest
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
            'name' => ['required', 'min:3', 'max:255'],
            'slug' => 'required|min:3|max:255',
            'status' => 'required'
        ];
    }

    public function messages(): array 
    {
        return [
            'name.required' => 'Ten buoc phai nhap !',
            'name.min' => 'Ten it nhat 3 ky tu',
            'name.max' => 'Ten nhieu nhat 255 ky tu',
            'slug.required' => 'Slug buoc phai nhap!',
            'status.required' => 'Status buoc phai nhap!'
        ];   
    }
}
