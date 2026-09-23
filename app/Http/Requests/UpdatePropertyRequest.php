<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use App\Models\Property;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
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
            'user_id' => 'sometimes|integer|exists:users,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'type' => [
                'sometimes',
                'integer',
                Rule::in([
                    Property::TYPE_APARTMENT,
                    Property::TYPE_HOUSE,
                    Property::TYPE_CHALET,
                    Property::TYPE_LAND,
                    Property::TYPE_COMMERCIAL,
                    Property::TYPE_GARAGE,
                    Property::TYPE_STORAGE,
                ]),
            ],
            'operation' => [
                'sometimes',
                'integer',
                Rule::in([
                    Property::OPERATION_SALE,
                    Property::OPERATION_RENT,
                ]),
            ],
            'bedrooms' => 'nullable|integer|min:0',
            'surface' => 'sometimes|numeric|min:0',
            'locality_id' => 'sometimes|integer|exists:localities,id',
            'address' => 'sometimes|string|max:255',
            'postal_code' => 'sometimes|string|max:20'
        ];
    }
}
