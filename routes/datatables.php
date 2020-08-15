<?php

/*
|--------------------------------------------------------------------------
| Datatables Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Datatable routes for your application. These
| routes are loaded by the RouteServiceProvider. Enjoy building your Datatable!
|
*/

Route::post('/user/list', 'UserController@getUserList')->name('users.list');
Route::post('/kp/list', 'RequestKPController@getKPRequestList')->name('kp_record.list');
Route::post('/skripsi/list', 'RequestSkripsiController@getSkripsiRequestList')->name('skripsi_record.list');
Route::post('/semester/list', 'SemesterController@getSemesterList')->name('semester.list');
Route::post('/faculty/list', 'FacultyController@getFacultyList')->name('faculty.list');
Route::post('/studyProgram/list', 'StudyProgramController@getStudyProgramList')->name('studyProgram.list');
Route::post('/ruanganSidang/list', 'RuanganSidangController@getRuanganSidangList')->name('ruanganSidang.list');
// this no used only for library
// Route::post('/hardcover_kp/list', 'HardcoverKPController@getListOfHardcoverKP')->name('hardcover_kp.list');
Route::post('/turnitin_kp/list', 'TurnitinFileController@getListOfTurnitinKPFiles')->name('turnitin_kp.list');

Route::prefix('student')->group(function () {
	Route::post('/list', 'AdminStudentController@getStudentList')->name('student.list');
	Route::post('/submitted/skripsi/list', 'AdminStudentController@getSkripsiSubmittedStudentList')->name('skripsi.student.list');
	Route::post('/submitted/tesis/list', 'AdminStudentController@getTesisSubmittedStudentList')->name('tesis.student.list');
	Route::post('/kp/list', 'StudentRequestKPController@getKPRequestList')->name('student.kp_record.list');
	Route::post('/skripsi/list', 'StudentRequestSkripsiController@getSkripsiRequestList')->name('student.skripsi_record.list');
	Route::post('/certificate/list', 'StudentCertificateController@getCertificateList')->name('student.certificate.list');
	Route::post('/achievement/list', 'StudentAchievementController@getAchievementList')->name('student.achievement.list');
});

Route::prefix('admin')->group(function () {
	// admin view student's profile
	Route::post('/student/certificate/list/{student}', 'AdminStudentController@getStudentCertificateList')->name('admin.student.certificate.list');
	Route::post('/student/achievement/list/{student}', 'AdminStudentController@getStudentAchievementList')->name('admin.student.achievement.list');
	Route::post('/student/session/status/list/{student}', 'AdminStudentController@getStudentSessionStatusList')->name('admin.student.session.status.list');
	// admin baak penjadwalan
	Route::post('/penjadwalan/kp/list', 'BaakPenjadwalanSidangKPController@getListOfPenjadwalanKP')->name('baak.penjadwalan_kp_list');
	Route::post('/penjadwalan/skripsi/list', 'BaakPenjadwalanSidangSkripsiController@getListOfPenjadwalanSkripsi')->name('baak.penjadwalan_skripsi_list');
	Route::post('/penjadwalan/tesis/list', 'BaakPenjadwalanSidangTesisController@getListOfPenjadwalanTesis')->name('baak.penjadwalan_tesis_list');
	// admin baak berita acara
	Route::post('/berita_acara/kp/list', 'BaakBeritaAcaraSidangKPController@getListBeritaAcaraSidangKPBaak')->name('baak.berita_acara_kp_list');
	Route::post('/berita_acara/skripsi/list', 'BaakBeritaAcaraSidangSkripsiController@getListBeritaAcaraSidangSkripsiBaak')->name('baak.berita_acara_skripsi_list');
	Route::post('/berita_acara/tesis/list', 'BaakBeritaAcaraSidangTesisController@getListBeritaAcaraSidangTesisBaak')->name('baak.berita_acara_tesis_list');
	// admin hardcover kp, skripsi, tesis
	Route::post('/hardcover_kp/lists', 'AdminHardcoverKPController@getListOfHardcoverKP')->name('admin.hardcover_kp.list');
	Route::post('/hardcover_skripsi/lists', 'AdminHardcoverSkripsiController@getListOfHardcoverSkripsi')->name('admin.hardcover_skripsi.list');
	Route::post('/hardcover_tesis/lists', 'AdminHardcoverTesisController@getListOfHardcoverTesis')->name('admin.hardcover_tesis.list');
	// study program user
	Route::post('/study_program_user/lists', 'BaakManagementProdiUserController@getListOfStudyProgramUsers')->name('admin.study_program.user.list');
});

Route::prefix('prodi')->group(function() {
	// prodi section
	Route::post('/prodi/dosen', 'ProdiUserController@getUserDosenList')->name('dosen.list');
	// request section
	Route::post('/kp/list', 'ProdiRequestKPController@getKPRequestList')->name('prodi.kp_record_list');
	Route::post('/skripsi/list', 'ProdiRequestSkripsiController@getSkripsiRequestList')->name('prodi.skripsi_record_list');
	// penjadwalan section
	Route::post('/penjadwalan/kp/list', 'ProdiPenjadwalanSidangKPController@getListOfPenjadwalanKP')->name('prodi.penjadwalan_kp_list');
	Route::post('/penjadwalan/skripsi/list', 'ProdiPenjadwalanSidangSkripsiController@getListOfPenjadwalanSkripsi')->name('prodi.penjadwalan_skripsi_list');
	Route::post('/penjadwalan/tesis/list', 'ProdiPenjadwalanSidangTesisController@getListOfPenjadwalanTesis')->name('prodi.penjadwalan_tesis_list');
	// baak section
	Route::post('/berita_acara/kp/list', 'ProdiBeritaAcaraSidangKPController@getListOfBeritaAcaraForEachParticipant')->name('prodi.berita_acara_kp_list');
	Route::post('/berita_acara/skripsi/list', 'ProdiBeritaAcaraSidangSkripsiController@getListOfBeritaAcaraForEachParticipant')->name('prodi.berita_acara_skripsi_list');
	Route::post('/berita_acara/tesis/list', 'ProdiBeritaAcaraSidangTesisController@getListOfBeritaAcaraForEachParticipant')->name('prodi.berita_acara_tesis_list');
	// prodi admin berita acara
	Route::post('/admin/berita_acara/kp/list', 'ProdiAdminBeritaAcaraSidangKPController@getListBeritaAcaraSidangProdi')->name('prodi_admin.berita_acara_kp_list');
	Route::post('/admin/berita_acara/skripsi/list', 'ProdiAdminBeritaAcaraSidangSkripsiController@getListBeritaAcaraSidangProdi')->name('prodi_admin.berita_acara_skripsi_list');
	Route::post('/admin/berita_acara/tesis/list', 'ProdiAdminBeritaAcaraSidangTesisController@getListBeritaAcaraSidangProdi')->name('prodi_admin.berita_acara_tesis_list');
});

Route::prefix('library')->group(function() {
	Route::post('/library/staff', 'LibraryUserController@getUserLibrary')->name('library_staff.list');
});

Route::prefix('finance')->group(function() {
	Route::post('/skripsi/list', 'FinanceRequestSkripsiController@getSkripsiRequestList')->name('finance.skripsi_record_list');
	Route::post('/finance/user', 'FinanceUserController@getUserFinance')->name('finance_user.list');
});