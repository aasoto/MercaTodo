<?php

namespace App\Actions\User;

use App\Classes\User\Roles;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\User;

class UpdateUserAction
{
    public function handle(UpdateRequest $request, Roles $roles, string $id): int
    {
        $data = $request->validated();
        $roles->update($id, $data["role_id"]);
        unset($data['role_id']);

        return User::where('id', $id)->update($data);
    }
}
