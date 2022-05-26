<?php

namespace App\Repositories\Api\V1;

use App\Repositories\BaseRepository;
use App\Model\Employee;

class EmployeeRepository extends BaseRepository
{
    public function model()
    {
        return Employee::class;
    }
    public function updateByModel(Employee $employee, array $data): Employee
    {
        $employee->first_name = isset($data['first_name']) ? $data['first_name'] : $employee->first_name;
        $employee->last_name = isset($data['last_name']) ? $data['last_name'] : $employee->last_name;
        $employee->company_id = isset($data['company_id']) ? $data['company_id'] : $employee->company_id;
        $employee->role_id = isset($data['role_id']) ? $data['role_id'] : $employee->role_id;
        $employee->department_id = isset($data['department_id']) ? $data['department_id'] : $employee->department_id;
        $employee->phone = isset($data['phone']) ? $data['phone'] : $employee->phone;
        $employee->email = isset($data['email']) ? $data['email'] : $employee->email;
        $employee->address = isset($data['address']) ? $data['address'] : $employee->address;
        $employee->updated_by = auth()->user()->id;
        if ($employee->isDirty()) {
            $employee->save();
        }
        return $employee->refresh()->load($this->relations);
    }
}
