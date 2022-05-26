<?php

use App\Model\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $role = [
        'Admin' => 'Admin Role - can access all',
        'Employee' => 'Employee Role'
    ];

    public function run()
    {
        Schema::disableForeignKeyConstraints();
        foreach ($this->role as $role => $description) {
            Role::create([
                'name'    => $role,
                'description' => $description
            ]);
        }
        Schema::enableForeignKeyConstraints();
    }
}
