<?php

use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudyProgramsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    // factory(App\Models\StudyProgram::class, 10)->create();
    	$user = User::where('username', 'Administrator')->first();

    	$hukumFaculty = Faculty::where('name', 'Hukum')->first();
    	$ekonomiFaculty = Faculty::where('name', 'Ekonomi')->first();
    	$teknoIndustriFaculty = Faculty::where('name', 'Teknologi Industri')->first();
    	$kompFaculty = Faculty::where('name', 'Ilmu Komputer')->first();
    	$sipilFaculty = Faculty::where('name', 'Teknik Sipil & Perencanaan')->first();
    	$pendFaculty = Faculty::where('name', 'Ilmu Pendidikan')->first();

        $studyProgram = new StudyProgram();
        $studyProgram->code = 'HKM';
        $studyProgram->name = 'Ilmu Hukum';
        $studyProgram->faculty_id = $hukumFaculty->id;
        $studyProgram->created_by = $user->id;
        if (! $studyProgram->save()) {
        	throw new Exception("Error saving ". $studyProgram->name);
        };
        $this->command->info($studyProgram->name. ' Created.');


        $studyProgram = new StudyProgram();
        $studyProgram->code = 'AKN';
        $studyProgram->name = 'Akuntansi';
        $studyProgram->faculty_id = $ekonomiFaculty->id;
        $studyProgram->created_by = $user->id;
        if (! $studyProgram->save()) {
        	throw new Exception("Error saving ". $studyProgram->name);
        };
        $this->command->info($studyProgram->name. ' Created.');

        $studyProgram = new StudyProgram();
        $studyProgram->code = 'MJN';
        $studyProgram->name = 'Manajemen';
        $studyProgram->faculty_id = $ekonomiFaculty->id;
        $studyProgram->created_by = $user->id;
        if (! $studyProgram->save()) {
        	throw new Exception("Error saving ". $studyProgram->name);
        };
        $this->command->info($studyProgram->name. ' Created.');

        $studyProgram = new StudyProgram();
        $studyProgram->code = 'PRW';
        $studyProgram->name = 'Pariwisata';
        $studyProgram->faculty_id = $ekonomiFaculty->id;
        $studyProgram->created_by = $user->id;
        if (! $studyProgram->save()) {
        	throw new Exception("Error saving ". $studyProgram->name);
        };
        $this->command->info($studyProgram->name. ' Created.');

        $studyProgram = new StudyProgram();
        $studyProgram->code = 'TE';
        $studyProgram->name = 'Teknik Elekto';
        $studyProgram->faculty_id = $teknoIndustriFaculty->id;
        $studyProgram->created_by = $user->id;
        if (! $studyProgram->save()) {
        	throw new Exception("Error saving ". $studyProgram->name);
        };
        $this->command->info($studyProgram->name. ' Created.');

        $studyProgram = new StudyProgram();
        $studyProgram->code = 'SI';
        $studyProgram->name = 'Sistem Informasi';
        $studyProgram->faculty_id = $kompFaculty->id;
        $studyProgram->created_by = $user->id;
        if (! $studyProgram->save()) {
        	throw new Exception("Error saving ". $studyProgram->name);
        };
        $this->command->info($studyProgram->name. ' Created.');

        $studyProgram = new StudyProgram();
        $studyProgram->code = 'TI';
        $studyProgram->name = 'Teknologi Informasi';
        $studyProgram->faculty_id = $kompFaculty->id;
        $studyProgram->created_by = $user->id;
        if (! $studyProgram->save()) {
        	throw new Exception("Error saving ". $studyProgram->name);
        };
        $this->command->info($studyProgram->name. ' Created.');

        $studyProgram = new StudyProgram();
        $studyProgram->code = 'TS';
        $studyProgram->name = 'Teknologi Sipil';
        $studyProgram->faculty_id = $sipilFaculty->id;
        $studyProgram->created_by = $user->id;
        if (! $studyProgram->save()) {
        	throw new Exception("Error saving ". $studyProgram->name);
        };
        $this->command->info($studyProgram->name. ' Created.');

        $studyProgram = new StudyProgram();
        $studyProgram->code = 'AS';
        $studyProgram->name = 'Arsitektur';
        $studyProgram->faculty_id = $sipilFaculty->id;
        $studyProgram->created_by = $user->id;
        if (! $studyProgram->save()) {
        	throw new Exception("Error saving ". $studyProgram->name);
        };
        $this->command->info($studyProgram->name. ' Created.');

        $studyProgram = new StudyProgram();
        $studyProgram->code = 'BHS';
        $studyProgram->name = 'Pendidikan Bahasa';
        $studyProgram->faculty_id = $pendFaculty->id;
        $studyProgram->created_by = $user->id;
        if (! $studyProgram->save()) {
        	throw new Exception("Error saving ". $studyProgram->name);
        };
        $this->command->info($studyProgram->name. ' Created.');

        $studyProgram = new StudyProgram();
        $studyProgram->code = 'ING';
        $studyProgram->name = 'Inggris';
        $studyProgram->faculty_id = $pendFaculty->id;
        $studyProgram->created_by = $user->id;
        if (! $studyProgram->save()) {
        	throw new Exception("Error saving ". $studyProgram->name);
        };
        $this->command->info($studyProgram->name. ' Created.');
    }
}
