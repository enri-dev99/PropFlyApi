<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\PropertyType;
use App\Enums\PropertyOperation;

class PropertyIndexRequest extends FormRequest
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
            'type' => ['sometimes', 'integer', Rule::in([PropertyType::APARTMENT, PropertyType::HOUSE, PropertyType::CHALET, PropertyType::LAND, PropertyType::COMMERCIAL, PropertyType::GARAGE, PropertyType::STORAGE])],
            'operation' => ['sometimes', 'integer', Rule::in([PropertyOperation::SALE, PropertyOperation::RENT])],
            'min_price' => ['sometimes', 'numeric', 'min:0', 'max:99999999.99'],
            'max_price' => ['sometimes', 'numeric', 'min:0', 'max:99999999.99', Rule::when(
                $this->filled('min_price'),
                ['gte:min_price']
            ),],
            'locality_id' => ['sometimes', 'integer', 'exists:localities,id'],
            'city_id' => ['sometimes', 'integer', 'exists:cities,id'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:50'],
            'sort_by' => ['sometimes', 'string', Rule::in(['price', 'created_at'])],
            'sort_direction' => ['sometimes', 'string', Rule::in(['asc', 'desc'])],
        ];
    }
}
