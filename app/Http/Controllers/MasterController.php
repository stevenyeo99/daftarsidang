<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;

class MasterController extends Controller
{
    protected $API_ID = '#shInta1499';

    protected $API_PASSWORD = '$kr!95h8^';

    protected $hardcoverAPIEndPoint = 'http://localhost/UIB_API/public/api/uib/library/student/hardcover';

    protected $transkripAPIEndPoint = 'http://localhost/UIB_API/public/api/uib/portal/student/transkrip';

    protected $saPoinAPIEndPoint = 'http://localhost/UIB_API/public/api/uib/portal/student/sa/activity/point';

    private $multipleFlashMessageArr = [];

    public function getBreadCrumbs(string $key)
    {
    	$breadCrumbs = array("Home");
    	switch ($key) {
            case 'kpRequest':
                $breadCrumbsToPush = ["Pendaftaran Kerja - Praktek"];
                break;
            case 'createKpRequest':
                $breadCrumbsToPush = ["Pendaftaran Kerja - Praktek", "Buat"];
                break;
            case 'viewKpRequest':
                $breadCrumbsToPush = ["Pendaftaran Kerja - Praktek", "Lihat"];
                break;
            case 'viewSkripsiRequest':
                $breadCrumbsToPush = ["Pendaftaran Skripsi / Tesis", "Lihat"];
                break;
            case 'editKp':
                $breadCrumbsToPush = ["Pendaftaran Kerja - Praktek", "Ubah"];
                break;
            case 'changeKpSessionStatus':
                $breadCrumbsToPush = ["Pendaftaran Kerja - Praktek", "Ubah Status Sidang"];
                break;
            case 'createSkripsiRequest':
                $breadCrumbsToPush = ["Pendaftaran Skripsi / Tesis", "Buat"];
                break;
            case 'editSkripsi':
                $breadCrumbsToPush = ["Pendaftaran Skripsi / Tesis", "Ubah"];
                break;
            case 'skripsiRequest':
                $breadCrumbsToPush = ["Pendaftaran Skripsi / Tesis"];
                break;
            case 'changeSkripsiSessionStatus':
                $breadCrumbsToPush = ["Pendaftaran Skripsi / Tesis", "Ubah Status Sidang"];
                break;
            case 'semesterList':
                $breadCrumbsToPush = ["Semester - semester"];
                break;
            case 'createSemester':
                $breadCrumbsToPush = ["Semester - semester", "Buat"];
                break;
            case 'editSemester':
                $breadCrumbsToPush = ["Semester - semester", "Ubah"];
                break;
            case 'facultyList':
                $breadCrumbsToPush = ["Fakultas - fakultas"];
                break;
            case 'createFaculty':
                $breadCrumbsToPush = ["Fakultas - fakultas", "Buat"];
                break;
            case 'editFaculty':
                $breadCrumbsToPush = ["Fakultas - fakultas", "Ubah"];
                break;
            case 'ruanganSidangList':
                $breadCrumbsToPush = ["Ruangan Sidang - ruangan sidang"];
                break;
            case 'createRuanganSidang':
                $breadCrumbsToPush = ["Ruangan Sidang - ruangan sidang", "Buat"];
                break;
            case 'editRuanganSidang':
                $breadCrumbsToPush = ["Ruangan Sidang - ruangan sidang", "Ubah"];
                break;
            case 'studyProgramList':
                $breadCrumbsToPush = ["Program Studi"];
                break;
            case 'createProdi':
                $breadCrumbsToPush = ["Program Studi", "Buat"];
                break;
            case 'editProdi':
                $breadCrumbsToPush = ["Program Studi", "Ubah"];
                break;
            case 'studentList':
                $breadCrumbsToPush = ["Mahasiswa - mahasiswa"];
                break;
            case 'meteorSkripsiStudentList':
                $breadCrumbsToPush = ["Mahasiswa - mahasiswa skripsi"];
                break;
            case 'meteorTesisStudentList':
                $breadCrumbsToPush = ["Mahasiswa - mahasiswa tesis"];
                break;
            case 'libraryList':
                $breadCrumbsToPush = ["Staff - user"];
                break;
            case 'createLibraryUser':
                $breadCrumbsToPush = ["Staff Library", "Buat"];
                break;
            case 'editLibraryUser':
                $breadCrumbsToPush = ["Staff Library", "Ubah"];
                break;
            case 'financeList':
                $breadCrumbsToPush = ["User - user"];
                break;
            case 'createFinanceUser':
                $breadCrumbsToPush = ["User", "Buat"];
                break;
            case 'editFinanceUser':
                $breadCrumbsToPush = ["User", "Ubah"];
                break;
            case 'userList':
                $breadCrumbsToPush = ["User - user"];
                break;
            case 'createUser':
                $breadCrumbsToPush = ["User", "Buat"];
                break;
            case 'editUser':
                $breadCrumbsToPush = ["User", "Ubah"];
                break;
            case 'userDosenList':
                $breadCrumbsToPush = ["Program Studi - user"];
                break;
            case 'adminDosenList':
                $breadCrumbsToPush = ["Program Studi - admin"];
                break;
            case 'createDosenUser':
                $breadCrumbsToPush = ["User Dosen", "Buat"];
                break;
            case 'createDosenAdmin':
                $breadCrumbsToPush = ["Admin Dosen", "Buat"];
                break;
            case 'editDosenUser':
                $breadCrumbsToPush = ["User Dosen", "Ubah"];
                break;
            case 'editDosenAdmin':
                $breadCrumbsToPush = ["Admin Dosen", "Ubah"];
                break;
            case 'assignDosenUser':
                $breadCrumbsToPush = ["User Dosen", "Atur"];
                break;
            case 'studentProfile':
                $breadCrumbsToPush = ["Data Profil"];
                break;
            case 'studentCertificateList':
                $breadCrumbsToPush = ["Sertifikasi"];
                break;
            case 'createCertificate':
                $breadCrumbsToPush = ["Sertifikasi", "Buat"];
                break;
            case 'editCertificate':
                $breadCrumbsToPush = ["Sertifikasi", "Ubah"];
                break;
            case 'studentAchievementList':
                $breadCrumbsToPush = ["Prestasi"];
                break;
            case 'createAchievement':
                $breadCrumbsToPush = ["Prestasi", "Buat"];
                break;
            case 'editAchievement':
                $breadCrumbsToPush = ["Prestasi", "Ubah"];
                break;
            case 'studentAttachment':
                $breadCrumbsToPush = ["Lampiran"];
                break;
            case 'adminStudentProfile':
                $breadCrumbsToPush = ["Profil Mahasiswa"];
                break;
            case 'hardcoverKPList':
                $breadCrumbsToPush = ["Hardcover KP"];
                break;
            case 'hardcoverSkripsiList':
                $breadCrumbsToPush = ["Hardcover Skripsi"];
                break;
            case 'hardcoverTesisList':
                $breadCrumbsToPush = ["Hardcover Tesis"];
                break;
            case 'turnitinKPList':
                $breadCrumbsToPush = ["Turnitin KP"];
                break;
            case 'turnitinKPCreate':
                $breadCrumbsToPush = ["Turnitin KP", "Buat"];
                break;
            case 'turnitinKPEdit':
                $breadCrumbsToPush = ["Turnitin KP", "Ubah"];
                break;
            case 'penjadwalanSidangKP':
                $breadCrumbsToPush = ["Penjadwalan Sidang KP"];
                break;
            case 'penjadwalanSidangSkripsi':
                $breadCrumbsToPush = ["Penjadwalan Sidang Skripsi"];
                break;
            case 'penjadwalanSidangTesis':
                $breadCrumbsToPush = ["Penjadwalan Sidang Tesis"];
                break;
            case 'formulirPenjadwalanSidangKP':
                $breadCrumbsToPush = ["Penjadwalan Sidang KP", 'Isi'];
                break;
            case 'formulirPenjadwalanSidangSkripsi':
                $breadCrumbsToPush = ["Penjadwalan Sidang Skripsi", 'Isi'];
                break;
            case 'formulirPenjadwalanSidangTesis':
                $breadCrumbsToPush = ["Penjadwalan Sidang Tesis", 'Isi'];
                break;
            case 'lihatPenjadwalanSidangKP':
                $breadCrumbsToPush = ["Penjadwalan Sidang KP", 'Lihat'];
                break;
            case 'lihatPenjadwalanSidangSkripsi':
                $breadCrumbsToPush = ["Penjadwalan Sidang Skripsi", 'Lihat'];
                break;
            case 'lihatPenjadwalanSidangTesis':
                $breadCrumbsToPush = ["Penjadwalan Sidang Tesis", 'Lihat'];
                break;
            case 'beritaAcaraSidangKP':
                $breadCrumbsToPush =  ["Berita Acara Sidang KP"];
                break;
            case 'beritaAcaraSidangSkripsi':
                $breadCrumbsToPush =  ["Berita Acara Sidang Skripsi"];
                break;
            case 'beritaAcaraSidangTesis':
                $breadCrumbsToPush =  ["Berita Acara Sidang Tesis"];
                break;
            case 'formulirBeritaAcaraSidangKP':
                $breadCrumbsToPush = ["Berita Acara Sidang KP", 'Isi'];
                break;
            case 'formulirBeritaAcaraSidangSkripsi':
                $breadCrumbsToPush = ["Berita Acara Sidang Skripsi", 'Isi'];
                break;
            case 'formulirBeritaAcaraSidangTesis':
                $breadCrumbsToPush = ["Berita Acara Sidang Tesis", 'Isi'];
                break;
    		default:
    			return $breadCrumbs;
        }
        $breadCrumbs = $this->pushBreadCrumb($breadCrumbs, $breadCrumbsToPush);
        return $breadCrumbs;
    }

    public function getTitle(string $key)
    {
    	switch ($key) {
            case 'kpRequest':
                $title = "Pendaftaran Kerja - Praktek";
                break;
            case 'createKpRequest':
                $title = "Buat Pendaftaran Kerja - Praktek";
                break;
            case 'viewKpRequest':
                $title = "Lihat Pendaftaran Kerja - Praktek";
                break;
            case 'editKp':
                $title = "Ubah Pendaftaran Kerja - Praktek";
                break;
            case 'changeKpSessionStatus':
                $title = "Ubah Status Sidang Pendaftar Kerja - Praktek";
                break;
            case 'viewSkripsiRequest':
                $title = "Lihat Pendaftaran Skripsi / Tesis";
                break;
            case 'createSkripsiRequest':
                $title = "Buat Pendaftaran Skripsi / Tesis";
                break;
            case 'editSkripsi':
                $title = "Ubah Pendaftaran Skripsi / Tesis";
                break;
            case 'skripsiRequest':
                $title = "Pendaftaran Skripsi / Tesis";
                break;
            case 'changeSkripsiSessionStatus':
                $title = "Ubah Status Sidang Pendaftar Skripsi / Tesis";
                break;
            case 'semesterList':
                $title = "Semester - semester";
                break;
            case 'createSemester':
                $title = "Buat Semester - semester";
                break;
            case 'editSemester':
                $title = "Ubah Semester - semester";
                break;
            case 'facultyList':
                $title = "Fakultas - fakultas";
                break;
            case 'createFaculty':
                $title = "Buat Fakultas - fakultas";
                break;
            case 'editFaculty':
                $title = "Ubah Fakultas - fakultas";
                break;
            case 'ruanganSidangList':
                $title = "Ruangan Sidang - ruangan sidang";
                break;
            case 'createRuanganSidang':
                $title = "Buat Ruangan Sidang - ruangan sidang";
                break;
            case 'editRuanganSidang':
                $title = "Ubah Ruangan Sidang - ruangan sidang";
                break;
            case 'studyProgramList':
                $title = "Program Studi";
                break;
            case 'createProdi':
                $title = "Buat Program Studi";
                break;
            case 'editProdi':
                $title = "Ubah Program Studi";
                break;
            case 'studentList':
                $title = "Mahasiswa - mahasiswa";
                break;
            case 'meteorSkripsiStudentList':
                $title = "Mahasiswa - mahasiswa skripsi";
                break;
            case 'meteorTesisStudentList':
                $title = "Mahasiswa - mahasiswa tesis";
                break;
            case 'userList':
                $title = "User - user";
                break;
            case 'createUser':
                $title = "Buat User";
                break;
            case 'editUser':
                $title = "Ubah User";
                break;
            case 'libraryList':
                $title = "Staff Library";
                break;
            case 'createLibraryUser':
                $title = "Buat User Staff Library";
                break;
            case 'editLibraryUser':
                $title = "Atur User Staff Library";
                break;
            case 'financeList':
                $title = "User Finance";
                break;
            case 'createFinanceUser':
                $title = "Buat User Finance";
                break;
            case 'editFinanceUser':
                $title = "Atur User Finance";
                break;
            case 'userDosenList':
                $title = "User Program Studi";
                break;
            case 'adminDosenList':
                $title = "Admin Program Studi";
                break;
            case 'createDosenUser':
                $title = "Buat User Dosen";
                break;
            case 'createDosenAdmin':
                $title = "Buat Admin Dosen";
                break;
            case 'editDosenUser':
                $title = "Ubah User Dosen";
                break;
            case 'editDosenAdmin':
                $title = 'Ubah Admin Dosen';
                break;
            case 'assignDosenUser':
                $title = 'Atur User Dosen';
                break;
            case 'studentProfile':
                $title = "Data Profil";
                break;
            case 'studentCertificateList':
                $title = "Sertifikasi";
                break;
            case 'createCertificate':
                $title = "Buat Sertifikasi";
                break;
            case 'editCertificate':
                $title = "Ubah Sertifikasi";
                break;
            case 'studentAchievementList':
                $title = "Prestasi";
                break;
            case 'createAchievement':
                $title = "Buat Prestasi";
                break;
            case 'editAchievement':
                $title = "Ubah Prestasi";
                break;
            case 'studentAttachment':
                $title = "Lampiran";
                break;
            case 'adminStudentProfile':
                $title = "Profil Mahasiswa";
                break;
            case 'hardcoverKPList':
                $title = "Hardcover KP";
                break;
            case 'hardcoverSkripsiList':
                $title = "Hardcover Skripsi";
                break;
            case 'hardcoverTesisList':
                $title = "Hardcover Tesis";
                break;
            case 'turnitinKPList':
                $title = "Turnitin KP";
                break;
            case 'turnitinKPCreate':
                $title = "Buat Turnitin KP";
                break;
            case 'turnitinKPEdit':
                $title = "Ubah Turnitin KP";
                break;
            case 'penjadwalanSidangKP':
                $title = "Penjadwalan Sidang KP";
                break;
            case 'penjadwalanSidangSkripsi':
                $title = "Penjadwalan Sidang Skripsi";
                break;
            case 'penjadwalanSidangTesis':
                $title = "Penjadwalan Sidang Tesis";
                break;
            case 'formulirPenjadwalanSidangKP':
                $title = "Isi Fomulir Penjadwalan Sidang KP";
                break;
            case 'formulirPenjadwalanSidangSkripsi':
                $title = "Isi Fomulir Penjadwalan Sidang Skripsi";
                break;
            case 'formulirPenjadwalanSidangTesis':
                $title = "Isi Fomulir Penjadwalan Sidang Tesis";
                break;
            case 'lihatPenjadwalanSidangKP':
                $title = 'Lihat Penjadwalan Sidang KP';
                break;
            case 'lihatPenjadwalanSidangSkripsi':
                $title = 'Lihat Penjadwalan Sidang Skripsi';
                break;
            case 'lihatPenjadwalanSidangTesis':
                $title = 'Lihat Penjadwalan Sidang Tesis';
                break;
            case 'beritaAcaraSidangKP':
                $title = "Berita Acara Sidang KP";
                break;
            case 'beritaAcaraSidangSkripsi':
                $title = "Berita Acara Sidang Skripsi";
                break;
            case 'beritaAcaraSidangTesis':
                $title = "Berita Acara Sidang Tesis";
                break;
            case 'formulirBeritaAcaraSidangKP':
                $title = "Isi Fomulir Berita Acara Sidang KP";
                break;
            case 'formulirBeritaAcaraSidangSkripsi':
                $title = "Isi Fomulir Berita Acara Sidang Skripsi";
                break;
            case 'formulirBeritaAcaraSidangTesis':
                $title = "Isi Fomulir Berita Acara Sidang Tesis";
                break;
    		default:
    			return "";
        }
        return $title;
    }

    public function getSubTitle(string $key)
    {
    	switch ($key) {
            case 'kpRequest':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol pendaftaran kerja - praktek";
                break;
            case 'createKpRequest':
                $sub_title = "sebuah halaman yang berguna untuk membuat data pendaftaran kerja - praktek baru";
                break;
            case 'viewKpRequest':
                $sub_title = "sebuah halaman yang berguna untuk memperlihatkan pendaftaran kerja - praktek";
                break;
            case 'viewSkripsiRequest':
                $sub_title = "sebuah halaman yang berguna untuk memperlihatkan pendaftaran skripsi / tesis";
                break;
            case 'editKp':
                $sub_title = "sebuah halaman yang berguna untuk mengubah pendaftaran kerja - praktek";
                break;
            case 'changeKpSessionStatus':
                $sub_title = "sebuah halaman yang berguna untuk mengubah status sidang pendaftar kerja - praktek";
                break;
            case 'createSkripsiRequest':
                $sub_title = "sebuah halaman yang berguna untuk membuat pendaftaran skripsi / tesis baru";
                break;
            case 'skripsiRequest':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol pendaftaran skripsi / tesis";
                break;
            case 'editSkripsi':
                $sub_title = "sebuah halaman yang berguna untuk mengubah pendaftaran skripsi / tesis";
                break;
            case 'changeSkripsiSessionStatus':
                $sub_title = "sebuah halaman yang berguna untuk mengubah status sidang pendaftar skripsi / tesis";
                break;
            case 'semesterList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data semester";
                break;
            case 'createSemester':
                $sub_title = "sebuah halaman yang berguna untuk membuat data semester baru";
                break;
            case 'editSemester':
                $sub_title = "sebuah halaman yang berguna untuk mengubah data semester";
                break;
            case 'facultyList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data fakultas";
                break;
            case 'createFaculty':
                $sub_title = "sebuah halaman yang berguna untuk membuat data fakultas baru";
                break;
            case 'editFaculty':
                $sub_title = "sebuah halaman yang berguna untuk mengubah data fakultas";
                break;
            case 'ruanganSidangList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data ruangan sidang";
                break;
            case 'createRuanganSidang':
                $sub_title = "sebuah halaman yang berguna untuk membuat data ruangan sidang baru";
                break;
            case 'editRuanganSidang':
                $sub_title = "sebuah halaman yang berguna untuk mengubah data ruangan sidang";
                break;
            case 'studyProgramList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data program studi";
                break;
            case 'createProdi':
                $sub_title = "sebuah halaman yang berguna untuk membuat data program studi baru";
                break;
            case 'editProdi':
                $sub_title = "sebuah halaman yang berguna untuk mengubah data program studi";
                break;
            case 'studentList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data mahasiswa";
                break;
            case 'meteorSkripsiStudentList':
                $sub_title = "sebuah halaman yang berguna untuk melihat data mahasiswa yang telah serahkan skripsi";
                break;
            case 'meteorTesisStudentList':
                $sub_title = "sebuah halaman yang berguna untuk melihat data mahasiswa yang telah serahkan tesis";
                break;
            case 'userList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data user";
                break;
            case 'createUser':
                $sub_title = "sebuah halaman yang berguna untuk membuat data user baru";
                break;
            case 'editUser':
                $sub_title = "sebuah halaman yang berguna untuk mengubah data user";
                break;
            case 'libraryList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data user staff library";
                break;
            case 'createLibraryUser':
                $sub_title = "sebuah halaman yang berguna untuk membuat data user staff library baru";
                break;
            case 'editLibraryUser':
                $sub_title = "sebuah halaman yang berguna untuk mengubah data user staff library";
                break;
            case 'financeList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data user finance";
                break;
            case 'createFinanceUser':
                $sub_title = "sebuah halaman yang berguna untuk membuat data user finance baru";
                break;
            case 'editFinanceUser':
                $sub_title = "sebuah halaman yang berguna untuk mengubah data user finance";
                break;
            case 'userDosenList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data user program studi";
                break;
            case 'adminDosenList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data admin program studi";
                break;
            case 'createDosenUser':
                $sub_title = "sebuah halaman yang berguna untuk membuat data user dosen baru";
                break;
            case 'createDosenAdmin':
                $sub_title = "sebuah halaman yang berguna untuk membuat data admin dosen baru";
                break;
            case 'editDosenUser':
                $sub_title = "sebuah halaman yang berguna untuk mengubah data user dosen";
                break;
            case 'editDosenAdmin':
                $sub_title = "sebuah halaman yang berguna untuk mengubah data admin dosen";
                break;
            case 'assignDosenUser':
                $sub_title = "sebuah halaman yang berguna untuk atur prodi pada user dosen";
                break;
            case 'studentProfile':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data profil anda";
                break;
            case 'studentCertificateList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data sertifikasi anda";
                break;
            case 'createCertificate':
                $sub_title = "sebuah halaman yang berguna untuk membuat data sertifikasi baru";
                break;
            case 'editCertificate':
                $sub_title = "sebuah halaman yang berguna untuk mengubah data sertifikasi";
                break;
            case 'studentAchievementList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data prestasi anda";
                break;
            case 'createAchievement':
                $sub_title = "sebuah halaman yang berguna untuk membuat data prestasi baru";
                break;
            case 'editAchievement':
                $sub_title = "sebuah halaman yang berguna untuk mengubah data prestasi";
                break;
            case 'studentAttachment':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data lampiran";
                break;
            case 'adminStudentProfile':
                $sub_title = "sebuah halaman yang berguna untuk melihat data profil mahasiswa";
                break;
            case 'hardcoverKPList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data Hardcover KP mahasiswa";
                break;
            case 'hardcoverSkripsiList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data Hardcover Skripsi mahasiswa";
                break;
            case 'hardcoverTesisList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data Hardcover Tesis mahasiswa";
                break;
            case 'turnitinKPList':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data Turnitin KP mahasiswa";
                break;
            case 'turnitinKPCreate':
                $sub_title = "sebuah halaman yang berguna untuk membuat data turnitin KP baru";
                break;
            case 'turnitinKPEdit':
                $sub_title = "sebuah halaman yang berguna untuk mengubah data turnitin KP baru";
                break;   	
            case 'penjadwalanSidangKP':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data Penjadwalan Sidang KP mahasiswa";
                break;
            case 'penjadwalanSidangSkripsi':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data Penjadwalan Sidang Skripsi mahasiswa";
                break;
            case 'penjadwalanSidangTesis':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data Penjadwalan Sidang Tesis mahasiswa";
                break;
            case 'formulirPenjadwalanSidangKP':
                $sub_title = "sebuah halaman yang berguna untuk mengisi data penjadwalan Sidang KP mahasiswa";
                break;
            case 'formulirPenjadwalanSidangSkripsi':
                $sub_title = "sebuah halaman yang berguna untuk mengisi data penjadwalan Sidang Skripsi mahasiswa";
                break;
            case 'formulirPenjadwalanSidangTesis':
                $sub_title = "sebuah halaman yang berguna untuk mengisi data penjadwalan Sidang Tesis mahasiswa";
                break;
            case 'lihatPenjadwalanSidangKP':
                $sub_title = 'sebuah halaman yang berguna untuk melihat data penjadwalan Sidang KP mahasiswa';
                break;
            case 'lihatPenjadwalanSidangSkripsi':
                $sub_title = 'sebuah halaman yang berguna untuk melihat data penjadwalan Sidang Skripsi mahasiswa';
                break;
            case 'lihatPenjadwalanSidangTesis':
                $sub_title = 'sebuah halaman yang berguna untuk melihat data penjadwalan Sidang Tesis mahasiswa';
                break;
            case 'beritaAcaraSidangKP':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data Berita Acara Sidang KP mahasiswa";
                break;
            case 'beritaAcaraSidangSkripsi':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data Berita Acara Sidang Skripsi mahasiswa";
                break;
            case 'beritaAcaraSidangTesis':
                $sub_title = "sebuah halaman yang berguna untuk mengontrol data Berita Acara Sidang Tesis mahasiswa";
                break;
            case 'formulirBeritaAcaraSidangKP':
                $sub_title = "sebuah halaman yang berguna untuk mengisi data Berita Acara Sidang KP mahasiswa";
                break;	
            case 'formulirBeritaAcaraSidangSkripsi':
                $sub_title = "sebuah halaman yang berguna untuk mengisi data Berita Acara Sidang Skripsi mahasiswa";
                break;
            case 'formulirBeritaAcaraSidangTesis':
                $sub_title = "sebuah halaman yang berguna untuk mengisi data Berita Acara Sidang Tesis mahasiswa";
                break;
    		default:
    			return "";
    	}
        return $sub_title;
    }

    public function setFlashMessage(string $alertType, string $message)
    {
        Session::flash('message', $message);
        Session::flash('alert-type', $alertType);
    }

    public function setMultipleFlashMessageArr(string $alertType, string $message)
    {
        array_push($this->multipleFlashMessageArr, ['alert-type' => $alertType, 'message' => $message]);
    }

    public function fireMultipleFlashMessage()
    {
        Session::flash('messages', $this->multipleFlashMessageArr);
    }

    public function resetMultipleFlashMessageArr()
    {
        $this->multipleFlashMessageArr = [];
    }

    public function getActionsButtons($model, array $extraClassToAdd = [])
    {
        $modelId = $model->id;
        $edit =  $this->getRoute('edit', $modelId);
        $destroy =  $this->getRoute('destroy', $modelId);
        $deleteClass = '';

        if (count($extraClassToAdd) > 0) {
            foreach ($extraClassToAdd as $key => $value) {
                if ($extraClassToAdd[$key] = 'delete') {
                    $deleteClass = $value;
                }
            }
        }

        if(Gate::allows('is-prodi-admin')) {
            return "<a href='{$edit}' title='UBAH' class='btn btn-warning'><span class='fa fa-pencil-square-o'></span> Ubah </a>
                    <a title='HAPUS' class='btn btn-danger delete-confirmation {$deleteClass}' data-toggle='modal' data-url='{$destroy}' data-id='{$modelId}' data-target='#delete-confirmation-modal'></span class='fa fa-trash-o'></span> Hapus </a>";
        }
        if(Gate::allows('is-library-admin') || Gate::allows('is-library-user')) {
            return "<a href='{$edit}' title='UBAH' class='btn btn-warning'><span class='fa fa-pencil-square-o'></span> Ubah </a>
                    <a title='HAPUS' class='btn btn-danger delete-confirmation {$deleteClass}' data-toggle='modal' data-url='{$destroy}' data-id='{$modelId}' data-target='#delete-confirmation-modal'><span class='fa fa-trash-o'></span> Hapus </a>";
        }
        if (Gate::allows('is-admin')) {
            return "<a href='{$edit}' title='UBAH' class='btn btn-warning'><span class='fa fa-pencil-square-o'></span> Ubah </a>
                   <a title='HAPUS' class='btn btn-danger delete-confirmation {$deleteClass}' data-toggle='modal' data-url='{$destroy}' data-id='{$modelId}' data-target='#delete-confirmation-modal'><span class='fa fa-trash-o'></span> Hapus </a>";
        }
        if (Gate::allows('is-student')) {
            return "<a href='{$edit}' title='UBAH' class='btn btn-warning'><span class='fa fa-pencil-square-o'></span> Ubah </a>
                   <a title='HAPUS' class='btn btn-danger delete-confirmation {$deleteClass}' data-toggle='modal' data-url='{$destroy}' data-id='{$modelId}' data-target='#delete-confirmation-modal'><span class='fa fa-trash-o'></span> Hapus </a>";
        }
        return "<a href='javascript:;' title='UBAH' class='btn btn-warning disabled'><span class='fa fa-pencil-square-o'></span> Ubah </a>
                   <a title='HAPUS' class='btn btn-danger delete-confirmation disabled {$deleteClass}'><span class='fa fa-trash-o'></span> Hapus </a>";
    }

    /**
     * for hard cover kp and skripsi that current is use only
     * 
     */
    public function getActionsDelete($model, array $extraClassToAdd = [])
    {
        $modelId = $model->id;
        $destroy = $this->getRoute('destroy', $modelId);
        $deleteClass = '';

        if(count($extraClassToAdd) > 0) {
            foreach($extraClassToAdd as $key => $value) {
                if($extraClassToAdd[$key] = 'delete') {
                    $deleteClass = $value;
                }
            }
        }

        // for library only that able to be delete
        if(Gate::allows('is-library-admin') || Gate::allows('is-library-user')) {
            return "<a title='HAPUS' class='btn btn-danger delete-confirmation {$deleteClass}' data-toggle='modal' data-url='{$destroy}' data-id='{$modelId}' data-target='#delete-confirmation-modal'><span class='fa fa-trash-o'></span> Hapus </a>";
        }

        return "";
    }

    /**
     * Get the error and redirect to route with errors and inputs.
     *
     * @param string $route, array $errors
     *
     * @return string
     */
    public function parseErrorAndRedirectToRouteWithErrors(string $route, $e)
    {
        if (is_string($e)) {
            $errors = new MessageBag([$e]);
        } elseif (method_exists($e, 'getMessage') ? $this->isArrayStringByJsonWay($e->getMessage()) : $this->isArrayStringByJsonWay($e)) {
            $errors = new MessageBag(method_exists($e, 'getMessage') ? json_decode($e->getMessage()) : json_encode($e));
        } else {
            $errors = new MessageBag(method_exists($e, 'getMessage') ? [$e->getMessage()] : $e->all());
        }
        return $this->redirectToRouteWithErrorsAndInputs($route, $errors);
    }

    /**
     * Redirect to route with errors and inputs.
     *
     * @param string $route, array $errors
     *
     * @return string
     */
    public function redirectToRouteWithErrorsAndInputs(string $route, $errors)
    {
        return redirect($route)
                ->with('errors', $errors)
                ->withInput();
    }

    /**
     * Check if the string is array or not.
     *
     * @param string $string
     *
     * @return boolean
     *
     * need to json_decode to change the string into array;
     */
    public function isArrayStringByJsonWay(string $string) {
        $string = json_decode($string);
        return is_array($string);
    }

    /**
     * check duplicates array value.
     *
     * @param $array, string $key
     *
     * @return boolean
     */
    public function multidimensional_has_dupes($arrays, string $key)
    {
        foreach ($arrays as $current_key => $current_array) {
            foreach ($arrays as $search_key => $search_array) {
                if ($search_array[$key] == $current_array[$key]) {
                    if ($search_key != $current_key) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Get file folder path for students.
     *
     * @param Student $student
     *
     * @return string
     */
    public function getStudentFileFolderPath(Student $student)
    {
        return public_path().'/images/'.$student->npm.'-'.$student->name.'/';
    }

    /**
     * pengesahan folder path
     */
    public function getStudentPengesahanFolderPath(Student $student, $type) 
    {
        if($type == 0) {
            return public_path().'/pengesahan-attachments/KP/'.$student->npm.'-'.$student->name.'/';
        } else if($type == 1) {
            return public_path().'/pengesahan-attachments/Skripsi/'.$student->npm.'-'.$student->name.'/';
        } else {
            return public_path().'/pengesahan-attachments/Tesis/'.$student->npm.'-'.$student->name.'/';
        }        
    }

    /**
     * kartu bimbingan folder path
     */
    public function getStudentKartuBimbinganFolderPath(Student $student, $type) 
    {
        if($type == 0) {
            return public_path().'/kartu_bimbingan-attachments/KP/'.$student->npm.'-'.$student->name.'/';
        } else if($type == 1) {
            return public_path().'/kartu_bimbingan-attachments/Skripsi/'.$student->npm.'-'.$student->name.'/';
        } else {
            return public_path().'/kartu_bimbingan-attachments/Tesis/'.$student->npm.'-'.$student->name.'/';
        }        
    }

    /**
     * get request attachment folder path
     */
    public function getStudentRequestAttachmentFolderPath(Student $student, $type, $requestId, $requestAttachmentType)
    {
        if($type == 0) {
            return public_path().'/'.$requestAttachmentType.'/'.'KP'.'/'.$student->npm.'-'.$student->name.'/'.$requestId.'/';
        } else if($type == 1) {
            return public_path().'/'.$requestAttachmentType.'/'.'SKRIPSI'.'/'.$student->npm.'-'.$student->name.'/'.$requestId.'/';
        } else {
            return public_path().'/'.$requestAttachmentType.'/'.'TESIS'.'/'.$student->npm.'-'.$student->name.'/'.$requestId.'/';
        }
    }
    

    private function pushBreadCrumb(array $arrToPush, array $breadCrumbsToPush)
    {
        if (count($breadCrumbsToPush) > 0) {
            foreach ($breadCrumbsToPush as $breadCrumbToPush) {
                array_push($arrToPush, $breadCrumbToPush);
            }
            return $arrToPush;
        }
    }

    /**
     * indonesian month
     */
    public function setIndonesianMonth($tanggal) {
        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        $splitItUp = explode('-', $tanggal);

        return $splitItUp[0] . ' ' . $bulan[(int)$splitItUp[1]] . ' ' . $splitItUp[2];
    }
}
