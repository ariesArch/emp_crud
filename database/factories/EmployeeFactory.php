<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    static $order = 2;
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone'        => $faker->numerify('##########'),
        'address'      => $faker->address,
        'company_id' => function () {
            return factory(App\Model\Company::class)->create()->id;
        },
        'department_id' => App\Model\Department::all()->random()->id,
        'role_id' => App\Model\Role::all()->random()->id,
        'staff_id' => str_pad($order++, 4, '0', STR_PAD_LEFT),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    ];
});
