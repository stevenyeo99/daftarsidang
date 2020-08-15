<?php

use App\Models\Faculty;
use App\Models\User;
use Illuminate\Database\Seeder;

class FacultiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    // factory(App\Models\Faculty::class, 7)->create();
    	$user = User::where('username', 'Administrator')->first();

        $faculty = new Faculty();
        $faculty->name = 'Hukum';
        $faculty->created_by = $user->id;
        if (! $faculty->save()) {
        	throw new Exception("Error saving ". $faculty->name);
        };
        $this->command->info($faculty->name. ' Created.');

        $faculty = new Faculty();
        $faculty->name = 'Ekonomi';
        $faculty->created_by = $user->id;
        if (! $faculty->save()) {
        	throw new Exception("Error saving ". $faculty->name);
        };
        $this->command->info($faculty->name. ' Created.');

        $faculty = new Faculty();
        $faculty->name = 'Teknologi Industri';
        $faculty->created_by = $user->id;
        if (! $faculty->save()) {
        	throw new Exception("Error saving ". $faculty->name);
        };
        $this->command->info($faculty->name. ' Created.');

        $faculty = new Faculty();
        $faculty->name = 'Ilmu Komputer';
        $faculty->created_by = $user->id;
        if (! $faculty->save()) {
        	throw new Exception("Error saving ". $faculty->name);
        };
        $this->command->info($faculty->name. ' Created.');

        $faculty = new Faculty();
        $faculty->name = 'Teknik Sipil & Perencanaan';
        $faculty->created_by = $user->id;
        if (! $faculty->save()) {
        	throw new Exception("Error saving ". $faculty->name);
        };
        $this->command->info($faculty->name. ' Created.');

        $faculty = new Faculty();
        $faculty->name = 'Ilmu Pendidikan';
        $faculty->created_by = $user->id;
        if (! $faculty->save()) {
        	throw new Exception("Error saving ". $faculty->name);
        };
        $this->command->info($faculty->name. ' Created.');
    }
}
