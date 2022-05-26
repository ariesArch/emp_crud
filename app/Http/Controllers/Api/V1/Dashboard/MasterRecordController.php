<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Role\RoleCollection;
use App\Http\Resources\Company\CompanyCollection;
use App\Http\Resources\Department\DepartmentCollection;
use App\Repositories\Api\V1\CompanyRepository;
use App\Repositories\Api\V1\DepartmentRepository;
use App\Repositories\Api\V1\RoleRepository;
use Illuminate\Http\Request;

class MasterRecordController extends Controller
{
    protected $companyRepo;
    protected $departmentRepository;
    protected $roleRepository;
    public function __construct(
        CompanyRepository $companyRepo,
        DepartmentRepository $departmentRepository,
        RoleRepository $roleRepository
    ) {
        $this->companyRepo = $companyRepo;
        $this->departmentRepository = $departmentRepository;
        $this->roleRepository = $roleRepository;
    }
    public function index()
    {
        $companies = $this->companyRepo->all();
        $departments = $this->departmentRepository->all();
        $roles = $this->roleRepository->all();
        return response()->json(
            [
                'status' => 1,
                'message' => 'Success',
                'data' => [
                    'companies' => new CompanyCollection($companies),
                    'departments' => new DepartmentCollection($departments),
                    'roles' => new RoleCollection($roles)
                ]
            ]
        );
    }
}
