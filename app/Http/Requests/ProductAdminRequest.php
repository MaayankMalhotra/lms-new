<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductAdminRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug,' . optional($this->route('product'))->id],
            'sku' => ['nullable', 'string', 'max:50'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'inventory' => ['required', 'integer', 'min:0'],
            'is_featured' => ['sometimes', 'boolean'],
            'status' => ['required', 'in:draft,published,archived'],
            'hero_image' => ['nullable', 'string', 'max:255'],
            'hero_image_file' => ['nullable', 'image', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'specifications' => ['nullable', 'string'],
            'categories' => ['array'],
            'categories.*' => ['exists:categories,id'],
        ];
    }
}
