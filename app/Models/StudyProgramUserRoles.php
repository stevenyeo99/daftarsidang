<?php

namespace App\Models;

use App\Models\BaseModel;

class StudyProgramUserRoles extends BaseModel {

    protected $table = 'study_program_user_roles';

    protected $fillable = [
        'study_program_user_id', 'study_program_id', 'created_by', 'updated_by',
    ];

    protected $dates = [
        'created_at', 'updated_at',
    ];

    // for prevent from timestamp got stored on db(need do manually)
    public $timestamps = false;

    /**
     * Get the errors from model validation.
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * The Prodi users that belong to the role.
     */
    public function studyProgramUsers() {
        return $this->belongsToMany('App\Models\StudyProgramUsers');
    }
}