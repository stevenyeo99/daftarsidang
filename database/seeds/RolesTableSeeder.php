<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'Super Admin';
        $role->code = 'SADM';
        if (! $role->save()) {
        	throw new Exception("Error saving ". $role->name);
        };
        $this->command->info($role->name. ' Created.');

        $role = new Role();
        $role->name = 'Admin';
        $role->code = 'ADM';
        if (! $role->save()) {
        	throw new Exception("Error saving ". $role->name);
        };
        $this->command->info($role->name. ' Created.');

        $role = new Role();
        $role->name = 'User';
        $role->code = 'USR';
        if (! $role->save()) {
        	throw new Exception("Error saving ". $role->name);
        };
        $this->command->info($role->name. ' Created.');
    }
}
