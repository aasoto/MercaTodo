<?php

namespace App\Traits;

use Spatie\Permission\Models\Role;

/**
 *
 */
trait AuthHasRole
{
    public function authHasRole($roles): string
    {
        foreach ($roles as $key => $value) {
            if (auth()->user()->hasRole($value['name'])) {
                return $value['name'];
            }
        }
        return '';
    }
}
