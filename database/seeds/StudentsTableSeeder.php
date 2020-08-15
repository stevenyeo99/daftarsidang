<?php

use App\Enums\ConsumptionType;
use App\Enums\Gender;
use App\Enums\SemesterType;
use App\Enums\TogaSize;
use App\Enums\WorkState;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudyProgram;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $oddSemester = Semester::where([['year', 2018], ['type', SemesterType::Odd]])->first();
        // $siStudyProgram = StudyProgram::where('code', 'SI')->first(); 

        $student = new Student();
        $student->npm = 1531069;
        $student->name = 'Jordy Julianto';
        $student->sex = Gender::Male;
        // $student->birth_place = 'Meral Karimun';
        // $student->birthdate = Carbon::parse('1997-07-04');
        // $student->religion = 'Buddha';
        // $student->email = 'jordyjulianto15@gmail.com';
        $student->password = bcrypt('123qwe');
        // $student->phone_number = '082385819123';
        // $student->address = 'Baloi One Residence Blok C No 5';
        // $student->work_status = WorkState::Work;
        // $student->toga_size = TogaSize::L;
        // $student->consumption_type = ConsumptionType::NonVegetarian;
        // $student->existing_degree = "";
        // $student->certification_degree = "";
        // $student->semester_id = $oddSemester->id;
        // $student->study_program_id = $siStudyProgram->id;
        // $student->company_id = null;
        if (! $student->save()) {
            throw new Exception("Error saving ". $student->name);
        };
        $this->command->info($student->name . ' Created.');

        $student = new Student();
        $student->npm = 1531062;
        $student->name = 'Senny Nicoria';
        $student->sex = Gender::Female;
        // $student->birth_place = 'Pekan Baru';
        // $student->birthdate = Carbon::parse('1997-02-23');
        // $student->religion = 'Buddha';
        // $student->email = 'sennynicoria@gmail.com';
        $student->password = bcrypt('123qwe');
        // $student->phone_number = '082385819124';
        // $student->address = 'Baloi Gatau Residence Blok C No 5';
        // $student->work_status = WorkState::Work;
        // $student->toga_size = TogaSize::M;
        // $student->consumption_type = ConsumptionType::NonVegetarian;
        // $student->existing_degree = "";
        // $student->certification_degree = "";
        // $student->semester_id = $oddSemester->id;
        // $student->study_program_id = $siStudyProgram->id;
        // $student->company_id = null;
        if (! $student->save()) {
            throw new Exception("Error saving ". $student->name);
        };
        $this->command->info($student->name . ' Created.');

        $student = new Student();
        $student->npm = 1531065;
        $student->name = 'Nana Charlyna';
        $student->sex = Gender::Female;
        // $student->birth_place = 'Selat Panjang';
        // $student->birthdate = Carbon::parse('1997-03-23');
        // $student->religion = 'Buddha';
        // $student->email = 'naacharlyna@gmail.com';
        $student->password = bcrypt('123qwe');
        // $student->phone_number = '082385819125';
        // $student->address = 'Baloi Gatau juga Residence Blok C No 5';
        // $student->work_status = WorkState::Work;
        // $student->toga_size = TogaSize::M;
        // $student->consumption_type = ConsumptionType::NonVegetarian;
        // $student->existing_degree = "";
        // $student->certification_degree = "";
        // $student->semester_id = $oddSemester->id;
        // $student->study_program_id = $siStudyProgram->id;
        // $student->company_id = null;
        if (! $student->save()) {
            throw new Exception("Error saving ". $student->name);
        };
        $this->command->info($student->name . ' Created.');

        $student = new Student();
        $student->npm = 1531070;
        $student->name = 'Steven';
        $student->sex = Gender::Male;
        // $student->birth_place = 'Batam';
        // $student->birthdate = Carbon::parse('1997-04-23');
        // $student->religion = 'Buddha';
        // $student->email = 'steven70@gmail.com';
        $student->password = bcrypt('123qwe');
        // $student->phone_number = '082385819126';
        // $student->address = 'Belakang DC mall Kayaknya Residence Blok C No 5';
        // $student->work_status = WorkState::Work;
        // $student->toga_size = TogaSize::M;
        // $student->consumption_type = ConsumptionType::Vegetarian;
        // $student->existing_degree = "";
        // $student->certification_degree = "";
        // $student->semester_id = $oddSemester->id;
        // $student->study_program_id = $siStudyProgram->id;
        // $student->company_id = null;
        if (! $student->save()) {
            throw new Exception("Error saving ". $student->name);
        };
        $this->command->info($student->name . ' Created.');

        $student = new Student();
        $student->npm = 1531013;
        $student->name = 'Febrianto';
        $student->sex = Gender::Male;
        // $student->birth_place = 'Tanjung Balai';
        // $student->birthdate = Carbon::parse('1997-04-23');
        // $student->religion = 'Buddha';
        // $student->email = 'febrianto@gmail.com';
        $student->password = bcrypt('123qwe');
        // $student->phone_number = '082385819127';
        // $student->address = 'Depan Uvers Kayaknya Residence Blok C No 5';
        // $student->work_status = WorkState::Work;
        // $student->toga_size = TogaSize::M;
        // $student->consumption_type = ConsumptionType::NonVegetarian;
        // $student->existing_degree = "";
        // $student->certification_degree = "";
        // $student->semester_id = $oddSemester->id;
        // $student->study_program_id = $siStudyProgram->id;
        // $student->company_id = null;
        if (! $student->save()) {
            throw new Exception("Error saving ". $student->name);
        };
        $this->command->info($student->name . ' Created.');
    }
}
