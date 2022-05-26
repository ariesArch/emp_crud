<?php

namespace App\Repositories\Api\V1;

use App\Repositories\BaseRepository;
use App\Model\Company;

class CompanyRepository extends BaseRepository
{
    public function model()
    {
        return Company::class;
    }
    public function updateByModel(Company $company, array $data): Company
    {
        $company->name = isset($data['name']) ? $data['name'] : $company->name;
        $company->email = isset($data['email']) ? $data['email'] : $company->email;
        $company->address = isset($data['address']) ? $data['address'] : $company->address;
        $company->updated_by = auth()->user()->id;
        if ($company->isDirty()) {
            $company->save();
        }
        return $company->refresh();
    }
}
