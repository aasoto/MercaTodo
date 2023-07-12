<?php

namespace App\Domain\User\Services\Contracts;

use App\Domain\User\Models\User;

interface FromQuery
{
    /**
     * @return User
     */
    public function query();
}
