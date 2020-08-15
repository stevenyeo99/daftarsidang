<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Enums\CreationType;

class Request extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'session', 'type', 'title', 'status', 'repeat_reason', 'reject_reason', 'mentor_name'
    ];

    // for file upload attribute
    public $file_upload;
    public $file_upload2;
    public $file_upload3;
    public $file_upload4;
    public $file_upload5;
    public $file_upload6;
    public $file_upload7;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
        'expiry_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that are errors from model validation.
     *
     * @var errors
     */
    protected $errors;

    /**
     * Get the student that owned the attachment
     */
    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }

    /**
     * Get the customized error message.
     */
    public function messages(string $key, string $keyTwo = null)
    {
        switch ($key) {
            case 'validation':
                return [
                    'student.required' => 'Mahasiswa tidak boleh kosong.',
                    'session.required' => 'Sesi tidak boleh kosong.',
                    'session.numeric' => 'Sesi harus berupa angka.',
                    'session.digits' => 'Sesi tidak boleh lewat dari 1 huruf atau angka.',
                    'type.required' => 'Jenis karya ilmiah tidak boleh kosong.',
                    'type.numeric' => 'Jenis karya ilmiah harus berupa angka.',
                    'title.required' => 'Judul karya ilmiah tidak boleh kosong.',
                    'title.max' => 'Judul karya ilmiah tidak boleh lewat dari 255 huruf atau angka.',
                    'status.required' => 'Status pendaftaran tidak boleh kosong.',
                    'status.numeric' => 'Status pendaftaran harus berupa angka.',
                    'repeat_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'reject_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'mentor_name.required' => 'Nama pembimbing tidak boleh kosong.',
                    'mentor_name.max' => 'Nama pembimbing tidak boleh lewat dari 50 huruf atau angka.',
                    'start_date.required' => 'Tanggal Mulai Bimbingan tidak boleh kosong.',
                    'start_date.date' => 'Tanggal Mulai Bimbingan harus berupa tanggal.',
                    'start_date.before' => 'Tanggal Mulai Bimbingan harus sebelum Tanggal Akhir Bimbingan.',
                    'end_date.required' => 'Tanggal Akhir Bimbingan tidak boleh kosong.',
                    'end_date.date' => 'Tanggal Akhir Bimbingan harus berupa tanggal.',
                    'end_date.before' => 'Tanggal Akhir Bimbingan tidak boleh lebih dari hari ini.',
                    'lembar_persetujuan.required' => 'Bukti lembar persetujuan harap dilampirkan.',
                    'lembar_persetujuan.mimes' => 'Format file lembar persetujuan harus berupa jpeg, png, jpg, pdf atau svg.',
                    'lembar_persetujuan.max' => 'Ukuran file lembar persetujuan tidak boleh lebih besar dari 1MB.',
                    'kartu_bimbingan.required' => 'Bukti kartu bimbingan harap dilampirkan.',
                    'kartu_bimbingan.mimes' => 'Format file kartu bimbingan harus berupa jpeg, png, jpg, pdf atau svg.',
                    'kartu_bimbingan.max' => 'Ukuran file kartu bimbingan tidak boleh lebih besar dari 1MB.',
                    'lembar_turnitin.required' => 'Bukti lembar turnitin harap dilampirkan.',
                    'lembar_turnitin.mimes' => 'Format file lembar turnitin harus berupa jpeg, png, jpg, pdf atau svg.',
                    'lembar_turnitin.max' => 'Ukuran file lembar turnitin tidak boleh lebih besar dari 1MB.',
                    'lembar_plagiat.required' => 'Bukti lembar plagiat harap dilampirkan.',
                    'lembar_plagiat.mimes' => 'Format lembar plagiat harus berupa jpeg, png, jpg, pdf atau svg.',
                    'lembar_plagiat.max' => 'Ukuran file lembar plagiat tidak boleh lebih besar dari 1MB.',
                    'foto_meteor.required' => 'Bukti foto meteor harap dilampirkan.',
                    'foto_meteor.mimes' => 'Format foto meteor harus berupa jpeg, png, jpg.',
                    'foto_meteor.max' => 'Ukuran file foto meteor tidak boleh lebih besar dari 1MB.',
                    'official_toeic.required' => 'Bukti lembar Official TOEIC harap dilampirkan.',
                    'official_toeic.mimes' => 'Format lembar Official TOEIC harus berupa jpeg, png, jpg, pdf atau svg.',
                    'official_toeic.max' => 'Ukuran file lembar official TOEIC tidak boleh lebih besar dari 1MB.',
                    'abstract_uclc.required' => 'Bukti lembar Abstract UCLC harap dilampirkan.',
                    'abstract_uclc.mimes' => 'Format lembar Abstract UCLC harus berupa jpeg, png, jpg, pdf atau svg.',
                    'abstract_uclc.max' => 'Ukuran file lembar Abstract UCLC tidak boleh lebih besar dari 1MB.',
                ];
            case 'validationKP':
                return [
                    'student.required' => 'Mahasiswa tidak boleh kosong.',
                    'session.required' => 'Sesi tidak boleh kosong.',
                    'session.numeric' => 'Sesi harus berupa angka.',
                    'session.digits' => 'Sesi tidak boleh lewat dari 1 huruf atau angka.',
                    'type.required' => 'Jenis karya ilmiah tidak boleh kosong.',
                    'type.numeric' => 'Jenis karya ilmiah harus berupa angka.',
                    'title.required' => 'Judul karya ilmiah tidak boleh kosong.',
                    'title.max' => 'Judul karya ilmiah tidak boleh lewat dari 255 huruf atau angka.',
                    'status.required' => 'Status pendaftaran tidak boleh kosong.',
                    'status.numeric' => 'Status pendaftaran harus berupa angka.',
                    'repeat_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'reject_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'mentor_name.required' => 'Nama pembimbing tidak boleh kosong.',
                    'mentor_name.max' => 'Nama pembimbing tidak boleh lewat dari 50 huruf atau angka.',
                    'start_date.required' => 'Tanggal Mulai Bimbingan tidak boleh kosong.',
                    'start_date.date' => 'Tanggal Mulai Bimbingan harus berupa tanggal.',
                    'start_date.before' => 'Tanggal Mulai Bimbingan harus sebelum Tanggal Akhir Bimbingan.',
                    'end_date.required' => 'Tanggal Akhir Bimbingan tidak boleh kosong.',
                    'end_date.date' => 'Tanggal Akhir Bimbingan harus berupa tanggal.',
                    'end_date.before' => 'Tanggal Akhir Bimbingan tidak boleh lebih dari hari ini.',
                    'lembar_persetujuan.required' => 'Bukti lembar persetujuan harap dilampirkan.',
                    'lembar_persetujuan.mimes' => 'Format file lembar persetujuan harus berupa jpeg, png, jpg, pdf atau svg.',
                    'lembar_persetujuan.max' => 'Ukuran file lembar persetujuan tidak boleh lebih besar dari 1MB.',
                    'kartu_bimbingan.required' => 'Bukti kartu bimbingan harap dilampirkan.',
                    'kartu_bimbingan.mimes' => 'Format file kartu bimbingan harus berupa jpeg, png, jpg, pdf atau svg.',
                    'kartu_bimbingan.max' => 'Ukuran file kartu bimbingan tidak boleh lebih besar dari 1MB.',
                    'lembar_turnitin.required' => 'Bukti lembar turnitin harap dilampirkan.',
                    'lembar_turnitin.mimes' => 'Format file lembar turnitin harus berupa jpeg, png, jpg, pdf atau svg.',
                    'lembar_turnitin.max' => 'Ukuran file lembar turnitin tidak boleh lebih besar dari 1MB.',
                ];
            case 'validationNoPersetujuan':
                return [
                    'student.required' => 'Mahasiswa tidak boleh kosong.',
                    'session.required' => 'Sesi tidak boleh kosong.',
                    'session.numeric' => 'Sesi harus berupa angka.',
                    'session.digits' => 'Sesi tidak boleh lewat dari 1 huruf atau angka.',
                    'type.required' => 'Jenis karya ilmiah tidak boleh kosong.',
                    'type.numeric' => 'Jenis karya ilmiah harus berupa angka.',
                    'title.required' => 'Judul karya ilmiah tidak boleh kosong.',
                    'title.max' => 'Judul karya ilmiah tidak boleh lewat dari 255 huruf atau angka.',
                    'status.required' => 'Status pendaftaran tidak boleh kosong.',
                    'status.numeric' => 'Status pendaftaran harus berupa angka.',
                    'repeat_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'reject_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'mentor_name.required' => 'Nama pembimbing tidak boleh kosong.',
                    'mentor_name.max' => 'Nama pembimbing tidak boleh lewat dari 50 huruf atau angka.',
                    'start_date.required' => 'Tanggal Mulai Bimbingan tidak boleh kosong.',
                    'start_date.date' => 'Tanggal Mulai Bimbingan harus berupa tanggal.',
                    'start_date.before' => 'Tanggal Mulai Bimbingan harus sebelum Tanggal Akhir Bimbingan.',
                    'end_date.required' => 'Tanggal Akhir Bimbingan tidak boleh kosong.',
                    'end_date.date' => 'Tanggal Akhir Bimbingan harus berupa tanggal.',
                    'end_date.before' => 'Tanggal Akhir Bimbingan tidak boleh lebih dari hari ini.',
                    'lembar_persetujuan.required' => 'Bukti lembar persetujuan harap dilampirkan.',
                    'lembar_persetujuan.mimes' => 'Format file lembar persetujuan harus berupa jpeg, png, jpg, pdf atau svg.',
                    'lembar_persetujuan.max' => 'Ukuran file lembar persetujuan tidak boleh lebih besar dari 1MB.',
                ];
            case 'validationNoBimbingan':
                return [
                    'student.required' => 'Mahasiswa tidak boleh kosong.',
                    'session.required' => 'Sesi tidak boleh kosong.',
                    'session.numeric' => 'Sesi harus berupa angka.',
                    'session.digits' => 'Sesi tidak boleh lewat dari 1 huruf atau angka.',
                    'type.required' => 'Jenis karya ilmiah tidak boleh kosong.',
                    'type.numeric' => 'Jenis karya ilmiah harus berupa angka.',
                    'title.required' => 'Judul karya ilmiah tidak boleh kosong.',
                    'title.max' => 'Judul karya ilmiah tidak boleh lewat dari 255 huruf atau angka.',
                    'status.required' => 'Status pendaftaran tidak boleh kosong.',
                    'status.numeric' => 'Status pendaftaran harus berupa angka.',
                    'repeat_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'reject_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'mentor_name.required' => 'Nama pembimbing tidak boleh kosong.',
                    'mentor_name.max' => 'Nama pembimbing tidak boleh lewat dari 50 huruf atau angka.',
                    'start_date.required' => 'Tanggal Mulai Bimbingan tidak boleh kosong.',
                    'start_date.date' => 'Tanggal Mulai Bimbingan harus berupa tanggal.',
                    'start_date.before' => 'Tanggal Mulai Bimbingan harus sebelum Tanggal Akhir Bimbingan.',
                    'end_date.required' => 'Tanggal Akhir Bimbingan tidak boleh kosong.',
                    'end_date.date' => 'Tanggal Akhir Bimbingan harus berupa tanggal.',
                    'end_date.before' => 'Tanggal Akhir Bimbingan tidak boleh lebih dari hari ini.',
                    'kartu_bimbingan.required' => 'Bukti kartu bimbingan harap dilampirkan.',
                    'kartu_bimbingan.mimes' => 'Format file kartu bimbingan harus berupa jpeg, png, jpg, pdf atau svg.',
                    'kartu_bimbingan.max' => 'Ukuran file kartu bimbingan tidak boleh lebih besar dari 1MB.',
                ];
            case 'validationNoTurnitin':
                return [
                    'student.required' => 'Mahasiswa tidak boleh kosong.',
                    'session.required' => 'Sesi tidak boleh kosong.',
                    'session.numeric' => 'Sesi harus berupa angka.',
                    'session.digits' => 'Sesi tidak boleh lewat dari 1 huruf atau angka.',
                    'type.required' => 'Jenis karya ilmiah tidak boleh kosong.',
                    'type.numeric' => 'Jenis karya ilmiah harus berupa angka.',
                    'title.required' => 'Judul karya ilmiah tidak boleh kosong.',
                    'title.max' => 'Judul karya ilmiah tidak boleh lewat dari 255 huruf atau angka.',
                    'status.required' => 'Status pendaftaran tidak boleh kosong.',
                    'status.numeric' => 'Status pendaftaran harus berupa angka.',
                    'repeat_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'reject_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'mentor_name.required' => 'Nama pembimbing tidak boleh kosong.',
                    'mentor_name.max' => 'Nama pembimbing tidak boleh lewat dari 50 huruf atau angka.',
                    'start_date.required' => 'Tanggal Mulai Bimbingan tidak boleh kosong.',
                    'start_date.date' => 'Tanggal Mulai Bimbingan harus berupa tanggal.',
                    'start_date.before' => 'Tanggal Mulai Bimbingan harus sebelum Tanggal Akhir Bimbingan.',
                    'end_date.required' => 'Tanggal Akhir Bimbingan tidak boleh kosong.',
                    'end_date.date' => 'Tanggal Akhir Bimbingan harus berupa tanggal.',
                    'end_date.before' => 'Tanggal Akhir Bimbingan tidak boleh lebih dari hari ini.',
                    'lembar_turnitin.required' => 'Bukti lembar turnitin harap dilampirkan.',
                    'lembar_turnitin.mimes' => 'Format file lembar turnitin harus berupa jpeg, png, jpg, pdf atau svg.',
                    'lembar_turnitin.max' => 'Ukuran file lembar turnitin tidak boleh lebih besar dari 1MB.',
                ];
             case 'validationNoPersetujuanBimbingan': // ketika lembar persetujuan dan kartu bimbingan gk diupload
                return [
                    'student.required' => 'Mahasiswa tidak boleh kosong.',
                    'session.required' => 'Sesi tidak boleh kosong.',
                    'session.numeric' => 'Sesi harus berupa angka.',
                    'session.digits' => 'Sesi tidak boleh lewat dari 1 huruf atau angka.',
                    'type.required' => 'Jenis karya ilmiah tidak boleh kosong.',
                    'type.numeric' => 'Jenis karya ilmiah harus berupa angka.',
                    'title.required' => 'Judul karya ilmiah tidak boleh kosong.',
                    'title.max' => 'Judul karya ilmiah tidak boleh lewat dari 255 huruf atau angka.',
                    'status.required' => 'Status pendaftaran tidak boleh kosong.',
                    'status.numeric' => 'Status pendaftaran harus berupa angka.',
                    'repeat_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'reject_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'mentor_name.required' => 'Nama pembimbing tidak boleh kosong.',
                    'mentor_name.max' => 'Nama pembimbing tidak boleh lewat dari 50 huruf atau angka.',
                    'start_date.required' => 'Tanggal Mulai Bimbingan tidak boleh kosong.',
                    'start_date.date' => 'Tanggal Mulai Bimbingan harus berupa tanggal.',
                    'start_date.before' => 'Tanggal Mulai Bimbingan harus sebelum Tanggal Akhir Bimbingan.',
                    'end_date.required' => 'Tanggal Akhir Bimbingan tidak boleh kosong.',
                    'end_date.date' => 'Tanggal Akhir Bimbingan harus berupa tanggal.',
                    'end_date.before' => 'Tanggal Akhir Bimbingan tidak boleh lebih dari hari ini.',
                    'lembar_persetujuan.required' => 'Bukti lembar persetujuan harap dilampirkan.',
                    'lembar_persetujuan.mimes' => 'Format file lembar persetujuan harus berupa jpeg, png, jpg, pdf atau svg.',
                    'lembar_persetujuan.max' => 'Ukuran file lembar persetujuan tidak boleh lebih besar dari 1MB.',
                    'kartu_bimbingan.required' => 'Bukti kartu bimbingan harap dilampirkan.',
                    'kartu_bimbingan.mimes' => 'Format file kartu bimbingan harus berupa jpeg, png, jpg, pdf atau svg.',
                    'kartu_bimbingan.max' => 'Ukuran file kartu bimbingan tidak boleh lebih besar dari 1MB.',
                ];
            case 'validationNoBimbinganTurnitin': // ketika kartu bimbingan dan turnitin gk diupload
                return [
                    'student.required' => 'Mahasiswa tidak boleh kosong.',
                    'session.required' => 'Sesi tidak boleh kosong.',
                    'session.numeric' => 'Sesi harus berupa angka.',
                    'session.digits' => 'Sesi tidak boleh lewat dari 1 huruf atau angka.',
                    'type.required' => 'Jenis karya ilmiah tidak boleh kosong.',
                    'type.numeric' => 'Jenis karya ilmiah harus berupa angka.',
                    'title.required' => 'Judul karya ilmiah tidak boleh kosong.',
                    'title.max' => 'Judul karya ilmiah tidak boleh lewat dari 255 huruf atau angka.',
                    'status.required' => 'Status pendaftaran tidak boleh kosong.',
                    'status.numeric' => 'Status pendaftaran harus berupa angka.',
                    'repeat_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'reject_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'mentor_name.required' => 'Nama pembimbing tidak boleh kosong.',
                    'mentor_name.max' => 'Nama pembimbing tidak boleh lewat dari 50 huruf atau angka.',
                    'start_date.required' => 'Tanggal Mulai Bimbingan tidak boleh kosong.',
                    'start_date.date' => 'Tanggal Mulai Bimbingan harus berupa tanggal.',
                    'start_date.before' => 'Tanggal Mulai Bimbingan harus sebelum Tanggal Akhir Bimbingan.',
                    'end_date.required' => 'Tanggal Akhir Bimbingan tidak boleh kosong.',
                    'end_date.date' => 'Tanggal Akhir Bimbingan harus berupa tanggal.',
                    'end_date.before' => 'Tanggal Akhir Bimbingan tidak boleh lebih dari hari ini.',
                    'kartu_bimbingan.required' => 'Bukti kartu bimbingan harap dilampirkan.',
                    'kartu_bimbingan.mimes' => 'Format file kartu bimbingan harus berupa jpeg, png, jpg, pdf atau svg.',
                    'kartu_bimbingan.max' => 'Ukuran file kartu bimbingan tidak boleh lebih besar dari 1MB.',
                    'lembar_turnitin.required' => 'Bukti lembar turnitin harap dilampirkan.',
                    'lembar_turnitin.mimes' => 'Format file lembar turnitin harus berupa jpeg, png, jpg, pdf atau svg.',
                    'lembar_turnitin.max' => 'Ukuran file lembar turnitin tidak boleh lebih besar dari 1MB.',
                ];
            case 'validationNoPersetujuanTurnitin': // ketika lembar persetujuan dan turnitin gk diupload
                return [
                    'student.required' => 'Mahasiswa tidak boleh kosong.',
                    'session.required' => 'Sesi tidak boleh kosong.',
                    'session.numeric' => 'Sesi harus berupa angka.',
                    'session.digits' => 'Sesi tidak boleh lewat dari 1 huruf atau angka.',
                    'type.required' => 'Jenis karya ilmiah tidak boleh kosong.',
                    'type.numeric' => 'Jenis karya ilmiah harus berupa angka.',
                    'title.required' => 'Judul karya ilmiah tidak boleh kosong.',
                    'title.max' => 'Judul karya ilmiah tidak boleh lewat dari 255 huruf atau angka.',
                    'status.required' => 'Status pendaftaran tidak boleh kosong.',
                    'status.numeric' => 'Status pendaftaran harus berupa angka.',
                    'repeat_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'reject_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'mentor_name.required' => 'Nama pembimbing tidak boleh kosong.',
                    'mentor_name.max' => 'Nama pembimbing tidak boleh lewat dari 50 huruf atau angka.',
                    'start_date.required' => 'Tanggal Mulai Bimbingan tidak boleh kosong.',
                    'start_date.date' => 'Tanggal Mulai Bimbingan harus berupa tanggal.',
                    'start_date.before' => 'Tanggal Mulai Bimbingan harus sebelum Tanggal Akhir Bimbingan.',
                    'end_date.required' => 'Tanggal Akhir Bimbingan tidak boleh kosong.',
                    'end_date.date' => 'Tanggal Akhir Bimbingan harus berupa tanggal.',
                    'end_date.before' => 'Tanggal Akhir Bimbingan tidak boleh lebih dari hari ini.',
                    'lembar_persetujuan.required' => 'Bukti lembar persetujuan harap dilampirkan.',
                    'lembar_persetujuan.mimes' => 'Format file lembar persetujuan harus berupa jpeg, png, jpg, pdf atau svg.',
                    'lembar_persetujuan.max' => 'Ukuran file lembar persetujuan tidak boleh lebih besar dari 1MB.',
                    'lembar_turnitin.required' => 'Bukti lembar turnitin harap dilampirkan.',
                    'lembar_turnitin.mimes' => 'Format file lembar turnitin harus berupa jpeg, png, jpg, pdf atau svg.',
                    'lembar_turnitin.max' => 'Ukuran file lembar turnitin tidak boleh lebih besar dari 1MB.',
                ];
            case 'validationNoPersetujuanBimbinganTurnitin':
                 return [
                    'student.required' => 'Mahasiswa tidak boleh kosong.',
                    'session.required' => 'Sesi tidak boleh kosong.',
                    'session.numeric' => 'Sesi harus berupa angka.',
                    'session.digits' => 'Sesi tidak boleh lewat dari 1 huruf atau angka.',
                    'type.required' => 'Jenis karya ilmiah tidak boleh kosong.',
                    'type.numeric' => 'Jenis karya ilmiah harus berupa angka.',
                    'title.required' => 'Judul karya ilmiah tidak boleh kosong.',
                    'title.max' => 'Judul karya ilmiah tidak boleh lewat dari 255 huruf atau angka.',
                    'status.required' => 'Status pendaftaran tidak boleh kosong.',
                    'status.numeric' => 'Status pendaftaran harus berupa angka.',
                    'repeat_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'reject_reason.max' => 'Alasan pengulangan tidak boleh lewat dari 255 huruf atau angka.',
                    'mentor_name.required' => 'Nama pembimbing tidak boleh kosong.',
                    'mentor_name.max' => 'Nama pembimbing tidak boleh lewat dari 50 huruf atau angka.',
                    'start_date.required' => 'Tanggal Mulai Bimbingan tidak boleh kosong.',
                    'start_date.date' => 'Tanggal Mulai Bimbingan harus berupa tanggal.',
                    'start_date.before' => 'Tanggal Mulai Bimbingan harus sebelum Tanggal Akhir Bimbingan.',
                    'end_date.required' => 'Tanggal Akhir Bimbingan tidak boleh kosong.',
                    'end_date.date' => 'Tanggal Akhir Bimbingan harus berupa tanggal.',
                    'end_date.before' => 'Tanggal Akhir Bimbingan tidak boleh lebih dari hari ini.',
                    'lembar_persetujuan' => 'Bukti lembar persetujuan harap dilampirkan',
                    'kartu_bimbingan.required' => 'Bukti kartu bimbingan harap dilampirkan.',
                    'kartu_bimbingan.mimes' => 'Format file kartu bimbingan harus berupa jpeg, png, jpg, pdf atau svg.',
                    'kartu_bimbingan.max' => 'Ukuran file kartu bimbingan tidak boleh lebih besar dari 1MB.',
                    'lembar_turnitin.required' => 'Bukti lembar turnitin harap dilampirkan.',
                    'lembar_turnitin.mimes' => 'Format file lembar turnitin harus berupa jpeg, png, jpg, pdf atau svg.',
                    'lembar_turnitin.max' => 'Ukuran file lembar turnitin tidak boleh lebih besar dari 1MB.',
                ];
            case 'success':
                switch ($keyTwo) {
                    case 'create':
                        return 'Telah membuat pendaftaran dengan sukses!';
                    case 'update':
                        return 'Telah memperbarui pendaftaran dengan sukses!';
                    case 'delete':
                        return 'Telah menghapus pendaftaran dengan sukses!';
                    case 'cancel':
                        return 'Telah membatalkan sidang dengan sukses!';
                    case 'import':
                        return 'Telah mengimpor pendaftaran dengan sukses!';
                    case 'penjadwalan':
                        return 'Telah memperbarui pendaftaran dengan sukses, segera melakukan penjadwalan sidang!';
                    default:
                        break;
                }
            case 'failDelete':
                    return 'Pendaftaran ini tidak bisa dihapus karena telah dipakai ditempat lain.';
            case 'failMappingExcelArray':
                    return 'Mapping dari excel terjadi error, bisa terjadi karena salah penamaan kolom, atau bisa karena nilai yang di input tidak benar.';
            case 'acceptRequest':
                    return 'Telah menerima pendaftaran dengan sukses!';
            case 'rejectRequest':
                    return 'Telah menolak pendaftaran dengan sukses!';
            case 'acceptFinanceRequest':
                    return 'Telah melakukan validasi keuangan valid dengan sukses!';
            case 'rejectFinanceRequest':
                    return 'Telah melakukan validasi keuangan tidak valid dengan sukses!';
            case 'failDeleteCauseOfStatus':
                    return 'Tidak boleh hapus pendaftaran dengan status selain draft!';
            case 'failSubmitRequest':
                    return 'Tidak boleh submit pendaftaran dengan status selain draft!';
            case 'failSubmitRequestLimitError':
                    return 'Batas submit pendaftaran tidak boleh lebih dari 1!';
            case 'failSubmitRequestSemesterInactiveError':
                    return 'Status semester sedang tidak aktif!';
            case 'failSubmitRequestParentInvalidError':
                    return 'Harap mengisi data orang tua terlebih dahulu pada bagian profil!';
            case 'failSubmitTesisRequestLimitAttachmentAndDegree':
                    return 'Harap mengisi gelar pada bagian profil dan upload lampiran ijazah S1 terlebih dahulu!';
            case 'emptyExcelRequest':
                    return 'Tidak dapat ekspor melalui excel karena data masih kosong!';
            case 'failSendMail':
                    return 'Terjadi error pada saat kirim email!';
            case 'failRequestKP':
                    return 'Harap sudah mempunyai hasil turnitin KP dari pihak Perpus!';
            case 'failRequestMultipleKP':
                    return 'Tidak dapat melakukan pendaftaran KP dikarenakan sudah ada pendaftaran yang sedang diproses!';
            case 'failRequestMultipleSkripsi':
                    return 'Tidak dapat melakukan pendaftaran Skripsi dikarenakan sudah ada pendaftaran yang sedang diproses!';
            case 'failRequestMultipleTesis':
                    return 'Tidak dapat melakukan pendaftaran Tesis dikarenakan sudah ada pendaftaran yang sedang diproses!';
            case 'failRequestKPAlreadyDone':
                    return 'Tidak dapat melakukan pendaftaran KP dikarenakan sudah lulus dari sidang kerja praktek';
            case 'failRequestSkripsiAlreadyDone':
                    return 'Tidak dapat melakukan pendaftaran Skripsi dikarenakan sudah lulus dari sidang skripsi';
            case 'failRequestTesisAlreadyDone':
                    return 'Tidak dapat melakukan pendaftaran Tesis dikarenakan sudah lulus dari sidang tesis';
            case 'failRequestSkripsi':
                    return 'Harap sudah mempunyai hasil turnitin Skripsi dari pihak Perpus!';
            case 'fileNotFoundOnDirectory':
                return 'File sebelumnya tidak ditemukan, silahkan hubungi administrator.';
            default:
                break;
        }
    }

    /**
     * Get the validation rules.
     */
    public function rules(Request $request)
    {
        if (isset($request->id)) { // when update
            $rules = [
                'session' => 'required|numeric|digits:1',
                'type' => 'required|numeric',
                'title' => 'required|max:255',
                'status' => 'required|numeric',
                'repeat_reason' => 'max:255',
                'reject_reason' => 'max:255',
                'mentor_name' => 'required|max:50',
                'student' => 'required',
            ];

            if ($request->type == CreationType::Skripsi || $request->type == CreationType::Tesis) {
                $rulesWannaAdd = [
                    'end_date' => 'required|date|before:today',
                    'start_date' => 'required|date|before:end_date',
                ];

                $rules += $rulesWannaAdd; // join two arrays
            }

            // for kp (lembar persetujuan, kartu bimbingan, lembar turnitin)
            // for skripsi (lembar persetujuan, kartu bimbingan, toeic, lembar plagiat, foto meteor, abstract uclc, lembar turnitin)
            if($request->file_upload == 'YES') {
                $lembarPersetujuanRules = [
                    'lembar_persetujuan' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                ];

                $rules += $lembarPersetujuanRules;
            }

            if($request->file_upload2 == 'YES') {
                $kartuBimbinganRules = [
                    'kartu_bimbingan' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                ];

                $rules += $kartuBimbinganRules;
            }

            if($request->file_upload3 == 'YES') {
                $lembarTurnitinRules = [
                    'lembar_turnitin' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                ];

                $rules += $lembarTurnitinRules;
            }
            
            if(($request->type == CreationType::Skripsi || $request->type == CreationType::Tesis) && $request->file_upload4 == 'YES') {
                $lembarPlagiat = [
                    'lembar_plagiat' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                ];

                $rules += $lembarPlagiat;
            }

            if(($request->type == CreationType::Skripsi || $request->type == CreationType::Tesis) && $request->file_upload5 == 'YES') {
                $fotoMeteor = [
                    'foto_meteor' => 'required|mimes:jpeg,png,jpg|max:1024',
                ];

                $rules += $fotoMeteor;
            }

            if(($request->type == CreationType::Skripsi || $request->type == CreationType::Tesis) && $request->file_upload6 == 'YES') {
                $officialToeic = [
                    'official_toeic' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                ];

                $rules += $officialToeic;
            }

            if(($request->type == CreationType::Skripsi || $request->type == CreationType::Tesis) && $request->file_upload7 == 'YES') {
                $abstractUclc = [
                    'abstract_uclc' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                ];

                $rules += $abstractUclc;
            }

            return $rules;
        } else { // when create
            $rules = [
                'session' => 'required|numeric|digits:1',
                'type' => 'required|numeric',
                'title' => 'required|max:255',
                'status' => 'required|numeric',
                'repeat_reason' => 'max:255',
                'reject_reason' => 'max:255',
                'mentor_name' => 'required|max:50',
                'student' => 'required',
            ];

            if ($request->type == CreationType::Skripsi || $request->type == CreationType::Tesis) {
                $rulesWannaAdd = [
                    'end_date' => 'required|date|before:today',
                    'start_date' => 'required|date|before:end_date',
                ];
                
                $rules += $rulesWannaAdd; // join two arrays
            }

            // for kp only now
            if($request->type == CreationType::KP) {
                $fileRules = [
                    'lembar_persetujuan' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                    'kartu_bimbingan' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                    'lembar_turnitin' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                ];
                
                $rules += $fileRules;
            } else {
                $fileRules = [
                    'lembar_persetujuan' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                    'kartu_bimbingan' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                    'lembar_turnitin' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                    'lembar_plagiat' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                    'foto_meteor' => 'required|mimes:jpeg,png,jpg|max:1024',
                    'official_toeic' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                    'abstract_uclc' => 'required|mimes:jpeg,png,jpg,pdf|max:1024',
                ];

                $rules += $fileRules;
            }

            return $rules;
        }
    }
}
