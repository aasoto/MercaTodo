<?php

namespace App\Domain\User\Services;

use App\Domain\User\Models\ModelHasRole;
use Spatie\Permission\Models\Role;

class RolesServices
{
    public function get(int $id): Role|null
    {
        return Role::select('name')->where('id', $id)->first();
    }

    public function update(string $id, int $role_id): int
    {
        return ModelHasRole::where('model_id', $id)->update(["role_id" => $role_id]);
    }
}
