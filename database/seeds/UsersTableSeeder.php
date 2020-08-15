<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     //    factory(App\Models\User::class, 50)->create()->each(function ($u) {
	    //     $u->roles()->sync(rand(2, 3));
	    //     // $u->faculties()->save(factory(App\Models\Faculty::class)->make());
	    //     // $u->studyPrograms()->save(factory(App\Models\StudyProgram::class)->make());
	    //     // $u->semesters()->save(factory(App\Models\Semester::class)->make());
	    // });

        $user = new User();
        $role = Role::where('code', 'SADM')->first();

        $user->username = 'Administrator';
        $user->email = 'admin@admin.com';
        $user->password = bcrypt('123qwe');

        if (! $user->save()) {
            throw new Exception("Error saving ". $user->username);
        };

        $user->roles()->sync([$role->id]);
        $this->command->info($user->username. ' Created.');
    }
}
