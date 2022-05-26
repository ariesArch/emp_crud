<?php

use App\Model\Department;
use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            ['name' => 'HR'],
            ['name' => 'IT'],
            ['name' => 'Finance']
        ];
        foreach ($departments as $department) {
            factory(Department::class)->create($department);
        }
    }
}
