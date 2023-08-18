<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type_document' => $this->type_document,
            'type_document_info' => TypeDocumentResource::make($this->whenLoaded('typeDocument')),
            'number_document' => $this->number_document,
            'first_name' => $this->first_name,
            'second_name' => $this->second_name,
            'surname' => $this->surname,
            'second_surname' => $this->second_surname,
            'email' => $this->email,
            'password' => $this->password,
            'birthdate' => $this->birthdate,
            'gender' => $this->gender,
            'address' => $this->address,
            'phone' => $this->phone,
            'state_id' => $this->state_id,
            'state' => StateResource::make($this->whenLoaded('state')),
            'city_id' => $this->city_id,
            'city' => CityResource::make($this->whenLoaded('city')),
            'enabled' => $this->enable,
        ];
    }
}
