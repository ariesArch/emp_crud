<?php

namespace App\Observers;

use App\Model\Employee;

class EmployeeObserver
{
    public function created(Employee $employee)
    {
        $employee->staff_id = $employee->id;
        $employee->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $employee->save();
    }
}
