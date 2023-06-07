<?php

namespace App\Domain\Order\Services\Entities\Placetopay;

class Buyer
{
    public function __construct(
        public mixed $user,
    )
    {}

    /**
     * @return array<mixed>
     */
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
