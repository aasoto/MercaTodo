<?php

namespace App\Domain\Order\Services\Entities\Placetopay;

use Illuminate\Contracts\Auth\Authenticatable;

class Buyer
{
    public function __construct(
        public Authenticatable $user,
    )
    {}

    public function getBuyer(): array
    {
        return [
            'document' => $this->user->number_document,
            'name' => $this->user->first_name.' '.$this->user->second_name,
            'surname' => $this->user->surname.' '.$this->user->second_surname,
            'mobile' => $this->user->phone,
            'email' => $this->user->email,
            'address' => $this->user->address,
        ];
    }
}
