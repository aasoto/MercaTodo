<?php

namespace App\Classes\User;

use App\Models\Spatie\ModelHasRole;
use Spatie\Permission\Models\Role;

class Roles
{
    public function get(int $id): Role|null
    {
        return Role::select('name')->where('id', $id)->first();
    }

    public function update(string $id, string $role_id): int
    {
        return ModelHasRole::where('model_id', $id)->update(["role_id" => $role_id]);
    }
}
