<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'type' => $this->type,
            'operation' => $this->operation,
            'bedrooms' => $this->bedrooms,
            'surface' => $this->surface,
            'locality' => [
                'id'=>$this->locality->id,
                'name'=>$this->locality->name
                ],
            'city' => [
                'id' => $this->locality->city->id,
                'name' => $this->locality->city->name
            ],
            'address' => $this->address,
            'postal_code' => $this->postal_code,
        ];
    }
}
