<?php

namespace App\Repositories\Api\V1;

use App\Repositories\BaseRepository;
use App\Model\Role;

class RoleRepository extends BaseRepository
{
    public function model()
    {
        return Role::class;
    }
    public function updateByModel(Role $role, array $data): Role
    {
        $role->name = isset($data['name']) ? $data['name'] : $role->name;
        $role->updated_by = auth()->user()->id;
        if ($role->isDirty()) {
            $role->save();
        }
        return $role->refresh();
    }
}
