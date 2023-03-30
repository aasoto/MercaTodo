<?php

namespace App\Traits;

use Spatie\Permission\Models\Role;

/**
 *
 */
trait AuthHasRole
{
    public function authHasRole(): string
    {
        foreach (Role::all() as $key => $value) {
            if (auth()->user()->hasRole($value['name'])) {
                return $value['name'];
            }
        }
        return '';
    }
}
