<?php

namespace App\Http\Resources\Employee;

use App\Http\Resources\Api\V1\Role\RoleResource;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Department\DepartmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->full_name,
            'staff_id' => $this->staff_id,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'company' => $this->when($this->relationLoaded('company'), function () {
                $company = CompanyResource::make($this->company);
                return $company->resource != null ? $company->only('id', 'name') : null;
            }),
            'department' => $this->when($this->relationLoaded('department'), function () {
                $department = DepartmentResource::make($this->department);
                return $department->resource != null ? $department->only('id', 'name') : null;
            }),
            'role' => $this->when($this->relationLoaded('role'), function () {
                $role = RoleResource::make($this->role);
                return $role->resource != null ? $role->only('id', 'name') : null;
            })
        ];
    }
    public function with($request)
    {
        return [
            'status' => 1,
        ];
    }
}
