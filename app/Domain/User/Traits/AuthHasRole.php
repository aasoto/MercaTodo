<?php

namespace App\Domain\User\Traits;

use Illuminate\Database\Eloquent\Collection;

trait AuthHasRole
{

    public function authHasRole(Collection $roles): string
    {
        foreach ($roles as $key => $value) {
            if (auth()->user()?->hasRole($value['name'])) {
                return $value['name'];
            }
        }
        return '';
    }
}
