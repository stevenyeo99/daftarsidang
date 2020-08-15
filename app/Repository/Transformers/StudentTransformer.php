<?php

namespace App\Repository\Transformers;

use App\Enums\CreationType;
use App\Enums\RequestStatus;

class StudentTransformer extends Transformer
{
    public function __construct() { }

    public function transform($student)
    {
        $student_program_study = $student->studyProgram()->first()->name;
        $student_generation = strtolower(substr($student->npm, 0, 2));

        return [
            'npm' => $student->npm,
            'name' => $student->name,
            'email' => $student->email,
            'program_study' => $student_program_study,
            'generation' => $student_generation,
            'session_date' => $student->session_date,
            'request_title' => $student->request_title,
            'request_status' => RequestStatus::getString($student->request_status),
            'request_type' => CreationType::getString($student->request_type),
            'request_mentor_name' => $student->request_mentor_name,
        ];
    }
}