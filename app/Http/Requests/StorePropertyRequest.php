<?php

namespace App\Http\Requests;

use App\Enums\PropertyOperation;
use App\Enums\PropertyType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
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
            'user_id' => 'nullable|integer|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'type' => [
                'required',
                'integer',
                Rule::enum(PropertyType::class),
            ],
            'operation' => [
                'required',
                'integer',
                Rule::enum(PropertyOperation::class),
            ],
            'bedrooms' => 'nullable|integer|min:0',
            'surface' => 'required|numeric|min:0',
            'locality_id' => 'required|integer|exists:localities,id',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20'
        ];
    }
}
