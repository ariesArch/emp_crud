<?php

namespace App\Repositories\Api\V1;

use App\Repositories\BaseRepository;
use App\Model\Department;

class DepartmentRepository extends BaseRepository
{
    public function model()
    {
        return Department::class;
    }
    public function updateByModel(Department $department, array $data): Department
    {
        $department->name = isset($data['name']) ? $data['name'] : $department->name;
        $department->updated_by = auth()->user()->id;
        if ($department->isDirty()) {
            $department->save();
        }
        return $department->refresh();
    }
}
