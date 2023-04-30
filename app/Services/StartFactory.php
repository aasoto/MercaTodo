<?php

namespace App\Services;

use App\Classes\Start\AdminMode;
use App\Classes\Start\ClientMode;
use App\Contracts\StartInterface;
use Exception;

class StartFactory
{
    public function initialize(string $user_role): StartInterface
    {
        if ($user_role == 'admin') {
            return new AdminMode();
        }

        if ($user_role == 'client') {
            return new ClientMode();
        }

        throw new Exception('User role not supported.');
    }
}
