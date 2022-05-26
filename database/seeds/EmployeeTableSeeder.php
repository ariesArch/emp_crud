<?php

use App\Model\Employee;
use Illuminate\Database\Seeder;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Employee::class)->create(
            [
                'staff_id' => '0001',
                'role_id' => 1
            ]
        );
        factory(Employee::class, 20)->create();
    }
}
