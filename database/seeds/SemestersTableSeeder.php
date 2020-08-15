<?php

use App\Enums\SemesterType;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;

class SemestersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('username', 'Administrator')->first();
        
        $semester = new Semester();
        $semester->year = '2015/2016';
        $semester->type = SemesterType::Odd;
        $semester->created_by = $user->id;
        $semester->text = $semester->year . " - " . SemesterType::getString($semester->type);
        if (! $semester->save()) {
            throw new Exception("Error saving ". $semester->year . " - " . SemesterType::getString($semester->type) );
        };
        $this->command->info($semester->text . ' Created.');
        
        $semester = new Semester();
        $semester->year = '2015/2016';
        $semester->type = SemesterType::Even;
        $semester->created_by = $user->id;
        $semester->text = $semester->year . " - " . SemesterType::getString($semester->type);
        if (! $semester->save()) {
            throw new Exception("Error saving ". $semester->year . " - " . SemesterType::getString($semester->type) );
        };
        $this->command->info($semester->text . ' Created.');
        
        // $semester = new Semester();
        // $semester->year = 2016;
        // $semester->type = SemesterType::Odd;
        // $semester->created_by = $user->id;
        // if (! $semester->save()) {
        //     throw new Exception("Error saving ". $semester->year . " - " . SemesterType::getString($semester->type) );
        // };
        // $this->command->info($semester->year . " - " . SemesterType::getString($semester->type) . ' Created.');
        
        // $semester = new Semester();
        // $semester->year = 2016;
        // $semester->type = SemesterType::Even;
        // $semester->created_by = $user->id;
        // if (! $semester->save()) {
        //     throw new Exception("Error saving ". $semester->year . " - " . SemesterType::getString($semester->type) );
        // };
        // $this->command->info($semester->year . " - " . SemesterType::getString($semester->type) . ' Created.');
        
        // $semester = new Semester();
        // $semester->year = 2017;
        // $semester->type = SemesterType::Odd;
        // $semester->created_by = $user->id;
        // if (! $semester->save()) {
        //     throw new Exception("Error saving ". $semester->year . " - " . SemesterType::getString($semester->type) );
        // };
        // $this->command->info($semester->year . " - " . SemesterType::getString($semester->type) . ' Created.');
        
        // $semester = new Semester();
        // $semester->year = 2017;
        // $semester->type = SemesterType::Even;
        // $semester->created_by = $user->id;
        // if (! $semester->save()) {
        //     throw new Exception("Error saving ". $semester->year . " - " . SemesterType::getString($semester->type) );
        // };
        // $this->command->info($semester->year . " - " . SemesterType::getString($semester->type) . ' Created.');
        
        // $semester = new Semester();
        // $semester->year = 2018;
        // $semester->type = SemesterType::Odd;
        // $semester->created_by = $user->id;
        // if (! $semester->save()) {
        //     throw new Exception("Error saving ". $semester->year . " - " . SemesterType::getString($semester->type) );
        // };
        // $this->command->info($semester->year . " - " . SemesterType::getString($semester->type) . ' Created.');
        
        // $semester = new Semester();
        // $semester->year = 2018;
        // $semester->type = SemesterType::Even;
        // $semester->created_by = $user->id;
        // if (! $semester->save()) {
        //     throw new Exception("Error saving ". $semester->year . " - " . SemesterType::getString($semester->type) );
        // };
        // $this->command->info($semester->year . " - " . SemesterType::getString($semester->type) . ' Created.');
        
        // $semester = new Semester();
        // $semester->year = 2019;
        // $semester->type = SemesterType::Odd;
        // $semester->created_by = $user->id;
        // if (! $semester->save()) {
        //     throw new Exception("Error saving ". $semester->year . " - " . SemesterType::getString($semester->type) );
        // };
        // $this->command->info($semester->year . " - " . SemesterType::getString($semester->type) . ' Created.');
        
        // $semester = new Semester();
        // $semester->year = 2019;
        // $semester->type = SemesterType::Even;
        // $semester->created_by = $user->id;
        // if (! $semester->save()) {
        //     throw new Exception("Error saving ". $semester->year . " - " . SemesterType::getString($semester->type) );
        // };
        // $this->command->info($semester->year . " - " . SemesterType::getString($semester->type) . ' Created.');
        
        // $semester = new Semester();
        // $semester->year = 2020;
        // $semester->type = SemesterType::Odd;
        // $semester->created_by = $user->id;
        // if (! $semester->save()) {
        //     throw new Exception("Error saving ". $semester->year . " - " . SemesterType::getString($semester->type) );
        // };
        // $this->command->info($semester->year . " - " . SemesterType::getString($semester->type) . ' Created.');
        
        // $semester = new Semester();
        // $semester->year = 2020;
        // $semester->type = SemesterType::Even;
        // $semester->created_by = $user->id;
        // if (! $semester->save()) {
        //     throw new Exception("Error saving ". $semester->year . " - " . SemesterType::getString($semester->type) );
        // };
        // $this->command->info($semester->year . " - " . SemesterType::getString($semester->type) . ' Created.');
    }
}
