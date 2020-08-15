<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class MeteorUserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'Meteor User';
        $role->code = 'MTRUSR';
        if (! $role->save()) {
        	throw new Exception("Error saving ". $role->name);
        };
        $this->command->info($role->name. ' Created.');
    }
}
