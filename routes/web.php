<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('student.login');
});

// Auth::routes();

// // Password Reset Routes...
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Route::get('/aw', 'ProdiBeritaAcaraSidangKPController@scheduleBeritaAcaraForm')->name('aw');

// Route::get('/aw', function() {
// 	return Hash::make('suzybae8');
// });

// test api
// Route::get('/aw', 'ProdiBeritaAcaraSidangKPController@scheduleBeritaAcaraForm')->name('aw');

// routes for admin
Route::prefix('admin')->group(function () {
	// Authentication Routes...
	Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
	Route::post('login', 'Auth\LoginController@login');
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');

	// routes for requests
	Route::group(['middleware' => 'can:is-admin-group'], function () {
		Route::prefix('request')->group(function () {
			Route::prefix('kp')->group(function () {
				Route::get('/', 'RequestKPController@index')->name('request.kp');
				Route::get('/view/{customRequest}', 'RequestKPController@viewRequest')->name('request.kp.view');
				// Route::get('/preview/{id}', 'RequestKPController@previewPengesahanFile')->name('baak.request.preview');
				// Route::get('/preview2/{id}', 'RequestKPController@previewKartuBimbingan')->name('baak.request.preview2');
				Route::post('/accept/{customRequest}', 'RequestKPController@acceptRequest')->name('request.kp.accept');
				Route::post('/reject/{customRequest}', 'RequestKPController@rejectRequest')->name('request.kp.reject');
				Route::post('/cancel/{customRequest}', 'RequestKPController@cancelRequest')->name('request.kp.cancel');
				// preview request attachment
				Route::get('/preview/{id}', 'RequestKPController@previewRequestAttachment')->name('baak.request.preview');

				Route::group(['middleware' => 'can:is-admin'], function () {
					Route::post('/download/request', 'RequestKPController@downloadRequestExcel')->name('request.kp.download');
					Route::get('/change/session/status/{customRequest}', 'RequestKPController@changeSessionStatus')->name('request.kp.change.session.status');
					Route::post('/change/session/status/{customRequest}', 'RequestKPController@updateSessionStatus')->name('request.kp.change.session.status');
				});
			});
			
			Route::prefix('skripsi')->group(function () {
				Route::get('/', 'RequestSkripsiController@index')->name('request.skripsi');
				Route::get('/view/{customRequest}', 'RequestSkripsiController@viewRequest')->name('request.skripsi.view');
				Route::post('/accept/{customRequest}', 'RequestSkripsiController@acceptRequest')->name('request.skripsi.accept');
				Route::post('/reject/{customRequest}', 'RequestSkripsiController@rejectRequest')->name('request.skripsi.reject');
				// preview request attachment
				Route::get('/preview/{id}', 'RequestSkripsiController@previewRequestAttachment')->name('baak.request.preview_ilmiah');

				Route::group(['middleware' => 'can:is-admin'], function () {
					Route::post('/download/request', 'RequestSkripsiController@downloadRequestExcel')->name('request.skripsi.download');
					Route::get('/change/session/status/{customRequest}', 'RequestSkripsiController@changeSessionStatus')->name('request.skripsi.change.session.status');
					Route::post('/change/session/status/{customRequest}', 'RequestSkripsiController@updateSessionStatus')->name('request.skripsi.change.session.status');
					Route::post('/cancel/{customRequest}', 'RequestSkripsiController@cancelRequest')->name('request.skripsi.cancel');
				});
			});
		});

		// routes for semesters
		Route::prefix('semester')->group(function () {
			Route::get('', 'SemesterController@index')->name('semesters');
			
			Route::group(['middleware' => 'can:is-admin'], function () {
				Route::get('/create', 'SemesterController@create')->name('semesters.create');
				Route::post('/create', 'SemesterController@store')->name('semesters.create');
				Route::get('/edit/{semester}', 'SemesterController@edit')->name('semesters.edit');
				Route::post('/edit/{semester}', 'SemesterController@update')->name('semesters.update');
				Route::delete('/destroy/{semester}', 'SemesterController@destroy')->name('semesters.destroy');
			});
		});

		// routes for ruangan sidang
		Route::prefix('ruangan')->group(function() {
			Route::get('', 'RuanganSidangController@index')->name('ruangan');

			Route::group(['middleware' => 'can:is-admin'], function() {
				Route::get('/create', 'RuanganSidangController@create')->name('ruangan.create');
				Route::post('/create', 'RuanganSidangController@store')->name('ruangan.create');
				Route::get('/edit/{ruangan}', 'RuanganSidangController@edit')->name('ruangan.edit');
				Route::post('/edit/{ruangan}', 'RuanganSidangController@update')->name('ruangan.update');
				Route::delete('/destroy/{ruangan}', 'RuanganSidangController@destroy')->name('ruangan.destroy');
			});
		});

		// routes for faculties
		Route::prefix('faculty')->group(function () {
			Route::get('', 'FacultyController@index')->name('faculties'); 
			
			Route::group(['middleware' => 'can:is-admin'], function () {
				Route::get('/create', 'FacultyController@create')->name('faculties.create');
				Route::post('/create', 'FacultyController@store')->name('faculties.create');
				Route::get('/edit/{faculty}', 'FacultyController@edit')->name('faculties.edit');
				Route::post('/edit/{faculty}', 'FacultyController@update')->name('faculties.update');
				Route::delete('/destroy/{faculty}', 'FacultyController@destroy')->name('faculties.destroy');
			});
		});

		// routes for study programs
		Route::prefix('prodis')->group(function () {
			Route::get('', 'StudyProgramController@index')->name('prodis');
			
			Route::group(['middleware' => 'can:is-admin'], function () {
				Route::get('/create', 'StudyProgramController@create')->name('prodis.create');
				Route::post('/create', 'StudyProgramController@store')->name('prodis.create');
				Route::get('/edit/{studyProgram}', 'StudyProgramController@edit')->name('prodis.edit');
				Route::post('/edit/{studyProgram}', 'StudyProgramController@update')->name('prodis.update');
				Route::delete('/destroy/{studyProgram}', 'StudyProgramController@destroy')->name('prodis.destroy');
			});
		});

		// routes for students
		Route::prefix('student')->group(function () {
			Route::get('', 'AdminStudentController@index')->name('students');
			
			Route::get('/{student}', 'AdminStudentController@viewDetail')->name('student.view.detail');
				
			Route::group(['middleware' => 'can:is-admin'], function () {
				Route::post('/download', 'AdminStudentController@downloadStudentExcel')->name('student.download');
			});

			Route::prefix('attachment/download')->group(function () {
				Route::get('/ktp/{student}', 'AdminStudentController@downloadKTP')->name('admin.student.attachment.download.ktp');
				Route::get('/kk/{student}', 'AdminStudentController@downloadKK')->name('admin.student.attachment.download.kk');
				Route::get('/ak/{student}', 'AdminStudentController@downloadAK')->name('admin.student.attachment.download.ak');
				Route::get('/sma/{student}', 'AdminStudentController@downloadIjazahSMA')->name('admin.student.attachment.download.ijazah.sma');
				Route::get('/s1/{student}', 'AdminStudentController@downloadIjazahS1')->name('admin.student.attachment.download.ijazah.s1');
			});
		});

		// routes for users
		Route::group(['middleware' => 'can:is-admin'], function () {
			Route::prefix('user')->group(function () {
				Route::get('', 'UserController@index')->name('users');
				
				Route::group(['middleware' => 'can:is-superadmin'], function () {
					Route::get('/create', 'UserController@create')->name('users.create');
					Route::post('/create', 'UserController@store')->name('users.create');
					Route::get('/edit/{user}', 'UserController@edit')->name('users.edit');
					Route::post('/edit/{user}', 'UserController@update')->name('users.update');
					Route::delete('/destroy/{user}', 'UserController@destroy')->name('users.destroy');
				});
			});

			// for baak management CRUD prodi user
			Route::prefix('prodi_user')->group(function() {
				Route::group(['middleware' => 'can:is-superadmin'], function() {
					Route::get('', 'BaakManagementProdiUserController@index')->name('baak.prodi_users');
					// user
					Route::get('/create', 'BaakManagementProdiUserController@create')->name('baak_prodi.create');
					Route::post('/create', 'BaakManagementProdiUserController@store')->name('baak_prodi.create');
					Route::get('/edit/{studyProgramUsers}', 'BaakManagementProdiUserController@edit')->name('baak_prodi.edit');
					Route::post('/edit/{studyProgramUsers}', 'BaakManagementProdiUserController@update')->name('baak_prodi.update');
					Route::delete('/destroy/{studyProgramUsers}', 'BaakManagementProdiUserController@destroy')->name('baak_prodi.destroy');
					// admin
					Route::get('/create_admin', 'BaakManagementProdiUserController@create_admin')->name('baak_prodi.create_admin');
					Route::post('/create_admin', 'BaakManagementProdiUserController@store_admin')->name('baak_prodi.create_admin');
				});
			});
		});

		// routes for penjadwalan
		Route::prefix('penjadwalan')->group(function() {
			Route::prefix('kp')->group(function() {
				Route::get('/', 'BaakPenjadwalanSidangKPController@index')->name('baak.penjadwalan.kp');
				Route::get('/assign/{penjadwalan}', 'BaakPenjadwalanSidangKPController@getPenjadwalanForStoreData')->name('baak.penjadwalan.assign.kp');
				Route::post('/assign/{penjadwalan}', 'BaakPenjadwalanSidangKPController@storePenjadwalanData')->name('baak.penjadwalan.assign.kp');
				Route::get('/view_penjadwalan/{penjadwalan}', 'BaakPenjadwalanSidangKPController@viewPenjadwalan')->name('baak.penjadwalan.view.kp');
				Route::post('/kirimUndangan/{penjadwalan}', 'BaakPenjadwalanSidangKPController@sendInvitation')->name('baak.penjadwalan.invitation.kp');
			});

			Route::prefix('skripsi')->group(function() {
				Route::get('/', 'BaakPenjadwalanSidangSkripsiController@index')->name('baak.penjadwalan.skripsi');
				Route::get('/assign/{penjadwalan}', 'BaakPenjadwalanSidangSkripsiController@getPenjadwalanForStoreData')->name('baak.penjadwalan.assign.skripsi');
				Route::post('/assign/{penjadwalan}', 'BaakPenjadwalanSidangSkripsiController@storePenjadwalanData')->name('baak.penjadwalan.assign.skripsi');
				Route::get('/view_penjadwalan/{penjadwalan}', 'BaakPenjadwalanSidangSkripsiController@viewPenjadwalan')->name('baak.penjadwalan.view.skripsi');
				Route::post('/kirimUndangan/{penjadwalan}', 'BaakPenjadwalanSidangSkripsiController@sendInvitation')->name('baak.penjadwalan.invitation.skripsi');
			});

			Route::prefix('tesis')->group(function() {
				Route::get('/', 'BaakPenjadwalanSidangTesisController@index')->name('baak.penjadwalan.tesis');
				Route::get('/assign/{penjadwalan}', 'BaakPenjadwalanSidangTesisController@getPenjadwalanForStoreData')->name('baak.penjadwalan.assign.tesis');
				Route::post('/assign/{penjadwalan}', 'BaakPenjadwalanSidangTesisController@storePenjadwalanData')->name('baak.penjadwalan.assign.tesis');
				Route::get('/view_penjadwalan/{penjadwalan}', 'BaakPenjadwalanSidangTesisController@viewPenjadwalan')->name('baak.penjadwalan.view.tesis');
				Route::post('/kirimUndangan/{penjadwalan}', 'BaakPenjadwalanSidangTesisController@sendInvitation')->name('baak.penjadwalan.invitation.tesis');
			});
		});

		// routes for berita acara
		Route::prefix('berita_acara')->group(function() {
			Route::prefix('kp')->group(function() {
				Route::get('/', 'BaakBeritaAcaraSidangKPController@index')->name('baak.berita_acara.kp');
				Route::post('/permission/{beritaAcara}', 'BaakBeritaAcaraSidangKPController@givePermission')->name('baak.berita_acara.permission_kp');
			});

			Route::prefix('skripsi')->group(function() {
				Route::get('/', 'BaakBeritaAcaraSidangSkripsiController@index')->name('baak.berita_acara.skripsi');
				Route::post('/permission/{beritaAcara}', 'BaakBeritaAcaraSidangSkripsiController@givePermission')->name('baak.berita_acara.permission_skripsi');
			});

			Route::prefix('tesis')->group(function() {
				Route::get('/', 'BaakBeritaAcaraSidangTesisController@index')->name('baak.berita_acara.tesis');
				Route::post('/permission/{beritaAcara}', 'BaakBeritaAcaraSidangTesisController@givePermission')->name('baak.berita_acara.permission_tesis');
			});
		});
	});


	// routes for meteor's user
	Route::group(['middleware' => 'can:is-meteor'], function () {
		Route::prefix('meteor')->group(function() {
			Route::get('/skripsi/student', 'AdminStudentController@meteorSkripsiIndex')->name('meteor.skripsi.students');
			Route::get('/tesis/student', 'AdminStudentController@meteorTesisIndex')->name('meteor.tesis.students');
		});
	});

	// routes for hard cover admin group user
	Route::group(['middleware' => 'can:is-admin-group'], function() {
		Route::prefix('hardcover_kp')->group(function() {
			Route::get('', 'AdminHardcoverKPController@index')->name('admin.hardcover_kp');
			Route::post('/download', 'AdminHardcoverKPController@downloadRequestExcel')->name('admin.hardcoverkp.download');
		});

		Route::prefix('hardcover_skripsi')->group(function() {
			Route::get('', 'AdminHardcoverSkripsiController@index')->name('admin.hardcover_skripsi');
			Route::post('/download', 'AdminHardcoverSkripsiController@downloadRequestExcel')->name('admin.hardcoverskripsi.download');
		});

		Route::prefix('hardcover_tesis')->group(function() {
			Route::get('', 'AdminHardcoverTesisController@index')->name('admin.hardcover_tesis');
			Route::post('/download', 'AdminHardcoverTesisController@downloadRequestExcel')->name('admin.hardcovertesis.download');
		});
	});
});

// routes for student
Route::prefix('student')->group(function() {
	// Authentication Routes...
    Route::get('/login', 'Auth\StudentLoginController@showLoginForm')->name('student.login');
    Route::post('/login', 'Auth\StudentLoginController@login')->name('student.login');
	Route::post('/logout', 'Auth\StudentLoginController@logout')->name('student.logout');
	    
	Route::group(['middleware' => 'can:is-student'], function () {
		Route::group(['middleware' => 'student.must.fill.attachment'], function () {
			// routes for profiles
		    Route::get('/profile', 'UserStudentController@profile')->name('student.profile');
		    Route::post('/profile', 'UserStudentController@updateProfile')->name('student.profile');
		    // Route::post('/profile/updateIsProfileAccurateFalse', 'UserStudentController@setStudentIsProfileAccurateFalse')->name('student.update.is.profile.accurate.false');
		    Route::post('/parent/profile', 'UserStudentController@updateParent')->name('student.parent.profile');
		    Route::post('/company/profile', 'UserStudentController@updateCompany')->name('student.company.profile');
		});

		Route::group(['middleware' => 'student.profile.filled'], function () {
			Route::group(['middleware' => 'student.must.fill.attachment'], function () {
				// routes for requests
				Route::prefix('request')->group(function () {
					Route::prefix('kp')->group(function () {
						Route::get('/', 'StudentRequestKPController@index')->name('student.request.kp');
						Route::get('/create', 'StudentRequestKPController@create')->name('student.request.create');
						Route::post('/create', 'StudentRequestKPController@store')->name('student.request.create');
						Route::get('/edit/{customRequest}', 'StudentRequestKPController@edit')->name('student.request.edit');
						Route::post('/edit/{customRequest}', 'StudentRequestKPController@update')->name('student.request.update');
						Route::delete('/destroy/{customRequest}', 'StudentRequestKPController@destroy')->name('student.request.destroy');
						Route::get('/view/{request}', 'StudentRequestKPController@viewRequest')->name('request.request.kp.view');
						// preview request attachment
						Route::get('/preview/{id}', 'StudentRequestKPController@previewRequestAttachment')->name('student.request.preview');
						Route::post('/update/status/kp/{customRequest}', 'StudentRequestKPController@updateRequestStatus')->name('student.request.update.status');
					});

					Route::prefix('skripsi')->group(function () {
						Route::get('/', 'StudentRequestSkripsiController@index')->name('student.request.skripsi');
						Route::get('/create', 'StudentRequestSkripsiController@create')->name('student.request.skripsi.create');
						Route::post('/create', 'StudentRequestSkripsiController@store')->name('student.request.skripsi.create');
						Route::get('/edit/{customRequest}', 'StudentRequestSkripsiController@edit')->name('student.request.skripsi.edit');
						Route::post('/edit/{customRequest}', 'StudentRequestSkripsiController@update')->name('student.request.skripsi.update');
						Route::delete('/destroy/{customRequest}', 'StudentRequestSkripsiController@destroy')->name('student.request.skripsi.destroy');
						Route::get('/view/{customRequest}', 'StudentRequestSkripsiController@viewRequest')->name('request.request.skripsi.view');
						// preview request attachment
						Route::get('/preview/{id}', 'StudentRequestSkripsiController@previewRequestAttachment')->name('student.request.preview_ilmiah');
						Route::post('/update/status/kp/{customRequest}', 'StudentRequestSkripsiController@updateRequestStatus')->name('student.request.skripsi.update.status');
					});
				});

				// routes for certificates
				Route::prefix('certificate')->group(function () {
					Route::get('/', 'StudentCertificateController@index')->name('student.certificate');
					Route::get('/create', 'StudentCertificateController@create')->name('student.certificate.create');
					Route::post('/create', 'StudentCertificateController@store')->name('student.certificate.create');
					Route::get('/edit/{certificate}', 'StudentCertificateController@edit')->name('student.certificate.edit');
					Route::post('/edit/{certificate}', 'StudentCertificateController@update')->name('student.certificate.update');
					Route::delete('/destroy/{certificate}', 'StudentCertificateController@destroy')->name('student.certificate.destroy');
				});

				// routes for achievements
				Route::prefix('achievement')->group(function () {
					Route::get('/', 'StudentAchievementController@index')->name('student.achievement');
					Route::get('/create', 'StudentAchievementController@create')->name('student.achievement.create');
					Route::post('/create', 'StudentAchievementController@store')->name('student.achievement.create');
					Route::get('/edit/{achievement}', 'StudentAchievementController@edit')->name('student.achievement.edit');
					Route::post('/edit/{achievement}', 'StudentAchievementController@update')->name('student.achievement.update');
					Route::delete('/destroy/{achievement}', 'StudentAchievementController@destroy')->name('student.achievement.destroy');
				});
			});

			// routes for attachments
			Route::prefix('attachment')->group(function () {
				Route::get('/', 'StudentAttachmentController@index')->name('student.attachment');

				Route::post('/ktp/create', 'StudentAttachmentController@uploadKTP')->name('student.attachment.upload.ktp');
				Route::get('/ktp/save', 'StudentAttachmentController@downloadKTP')->name('student.attachment.download.ktp');

				Route::post('/kk/create', 'StudentAttachmentController@uploadKK')->name('student.attachment.upload.kk');
				Route::get('/kk/save', 'StudentAttachmentController@downloadKK')->name('student.attachment.download.kk');

				Route::post('/ak/create', 'StudentAttachmentController@uploadAK')->name('student.attachment.upload.ak');
				Route::get('/ak/save', 'StudentAttachmentController@downloadAK')->name('student.attachment.download.ak');

				Route::post('/ijazah/sma/create', 'StudentAttachmentController@uploadIjazahSMA')->name('student.attachment.upload.ijazah.sma');
				Route::get('/ijazah/sma/save', 'StudentAttachmentController@downloadIjazahSMA')->name('student.attachment.download.ijazah.sma');

				Route::post('/ijazah/s1/create', 'StudentAttachmentController@uploadIjazahS1')->name('student.attachment.upload.ijazah.s1');
				Route::get('/ijazah/s1/save', 'StudentAttachmentController@downloadIjazahS1')->name('student.attachment.download.ijazah.s1');
			});
		});
	});
});

// routes for library
// no used again :(
// Route::prefix('library')->group(function() {
// 	Route::get('/login', 'Auth\LibraryLoginController@showLoginForm')->name('library.login');
// 	Route::post('/login', 'Auth\LibraryLoginController@login')->name('library.login');
// 	Route::post('/logout', 'Auth\LibraryLoginController@logout')->name('library.logout');

// 	// for admin only
// 	Route::group(['middleware' => 'can:is-library-admin'], function() {
// 		Route::prefix('staff')->group(function() {
// 			Route::get('', 'LibraryUserController@index')->name('library_staff');
// 			Route::get('/create', 'LibraryUserController@create')->name('library_staff.create');
// 			Route::post('/create', 'LibraryUserController@store')->name('library_staff.create');
// 			Route::get('/edit/{libraryUser}', 'LibraryUserController@edit')->name('library_staff.edit');
// 			Route::post('/edit/{libraryUser}', 'LibraryUserController@update')->name('library_staff.update');
// 			Route::delete('/destroy/{libraryUser}', 'LibraryUserController@destroy')->name('library_staff.destroy');
// 		});
// 	});

// 	// hard cover kp
// 	Route::prefix('hardcover_kp')->group(function() {			
// 		Route::get('', 'HardcoverKPController@index')->name('hardcover_kp');				
// 		Route::post('/import', 'HardcoverKPController@doImportHardCoverKPData')->name('hardcover_kp.import');
// 		Route::delete('/destroy/{hardcoverKP}', 'HardcoverKPController@destroy')->name('hardcover_kp.destroy');	
// 		Route::post('/download', 'HardcoverKPController@downloadRequestExcel')->name('library.hardcoverkp.download');
// 	});

// 	// turnitin kp
// 	Route::prefix('turnitin_kp')->group(function() {
// 		Route::get('', 'TurnitinFileController@index')->name('turnitin_kp');
// 		Route::get('/create', 'TurnitinFileController@create')->name('turnitin_kp.create');
// 		Route::post('/create', 'TurnitinFileController@store')->name('turnitin_kp.store');
// 		Route::get('/edit/{turnitinFile}', 'TurnitinFileController@edit')->name('turnitin_kp.edit');
// 		Route::post('/edit/{turnitinFile}', 'TurnitinFileController@update')->name('turnitin_kp.update');
// 		Route::delete('/destroy/{turnitinFile}', 'TurnitinFileController@destroy')->name('turnitin_kp.destroy');
// 		Route::get('/download/{id}', 'TurnitinFileController@downloadFileTurnitin')->name('turnitin_kp.download');
// 		Route::get('/preview/{id}', 'TurnitinFileController@previewFileTurnitin')->name('turnitin_kp.preview');
// 	});
// });

// for finance route
Route::prefix('finance')->group(function() {
	// Authentication Routes
	Route::get('/login', 'Auth\FinanceLoginController@showLoginForm')->name('finance.login');
	Route::post('/login', 'Auth\FinanceLoginController@login')->name('finance.login');
	Route::post('/logout', 'Auth\FinanceLoginController@logout')->name('finance.logout');

	Route::group(['middleware' => 'can:is-finance-group'], function() {
		Route::prefix('request')->group(function() {
			Route::prefix('skripsi')->group(function() {
				Route::get('/', 'FinanceRequestSkripsiController@index')->name('finance.request.skripsi');
				// view
				Route::get('/view/{customRequest}', 'FinanceRequestSkripsiController@viewRequest')->name('finance.request.skripsi.view');
				// accept
				Route::post('/accept/{customRequest}', 'FinanceRequestSkripsiController@acceptRequest')->name('finance.request.skripsi.accept');
				// reject
				Route::post('/reject/{customRequest}', 'FinanceRequestSkripsiController@rejectRequest')->name('finance.request.skripsi.reject');
			});
		});

		Route::prefix('users')->group(function() {
			Route::get('', 'FinanceUserController@index')->name('finance_user');
			Route::get('/create', 'FinanceUserController@create')->name('finance_user.create');
			Route::post('/create', 'FinanceUserController@store')->name('finance_user.create');
			Route::get('/edit/{financeUser}', 'FinanceUserController@edit')->name('finance_user.edit');
			Route::post('/edit/{financeUser}', 'FinanceUserController@update')->name('finance_user.update');
			Route::delete('/destroy/{financeUser}', 'FinanceUserController@destroy')->name('finance_user.destroy');			
		});
	});
});

// routes for prodi
Route::prefix('prodi')->group(function() {
	// Authentication Routes...
	Route::get('/login', 'Auth\ProdiLoginController@showLoginForm')->name('prodi.login');
	Route::post('/login', 'Auth\ProdiLoginController@login')->name('prodi.login');
	Route::post('/logout', 'Auth\ProdiLoginController@logout')->name('prodi.logout');
	
	Route::group(['middleware' => 'can:is-prodi-admin'], function () { 
		// request section
		Route::prefix('request')->group(function() {
			// request kp section
			Route::prefix('kp')->group(function() {
				Route::get('/', 'ProdiRequestKPController@index')->name('prodi.request.kp');

				Route::get('/change/session/status/{customRequest}', 'ProdiRequestKPController@changeSessionStatus')->name('prodi.request.kp.change.session.status');
				Route::post('/change/session/status/{customRequest}', 'ProdiRequestKPController@updateSessionStatus')->name('prodi.request.kp.change.session.status');
				
				Route::post('/accept/{customRequest}', 'ProdiRequestKPController@acceptRequest')->name('prodi.request.kp.accept');
				Route::post('/reject/{customRequest}', 'ProdiRequestKPController@rejectRequest')->name('prodi.request.kp.reject');
				Route::post('/cancel/{customRequest}', 'ProdiRequestKPController@cancelRequest')->name('prodi.request.kp.cancel');

				Route::get('/view/{customRequest}', 'ProdiRequestKPController@viewRequest')->name('prodi.request.kp.view');
				Route::get('/preview/{id}', 'ProdiRequestKPController@previewRequestAttachment')->name('prodi.request.preview');
			});
			// request kp skripsi section
			Route::prefix('skripsi')->group(function() {
				Route::get('/', 'ProdiRequestSkripsiController@index')->name('prodi.request.skripsi');

				// accept
				Route::post('/accept/{customRequest}', 'ProdiRequestSkripsiController@acceptRequest')->name('prodi.request.skripsi.accept');
				// reject
				Route::post('/reject/{customRequest}', 'ProdiRequestSkripsiController@rejectRequest')->name('prodi.request.skripsi.reject');
				// cancel
				Route::post('/cancel/{customRequest}', 'ProdiRequestSkripsiController@cancelRequest')->name('prodi.request.skripsi.cancel');

				// view transkrip
				Route::get('/viewTranskrip/{npm}', 'ProdiRequestSkripsiController@viewTranskripMahasiswa')->name('prodi.request.skripsi.view_transkrip');

				// view
				Route::get('/view/{customRequest}', 'ProdiRequestSkripsiController@viewRequest')->name('prodi.request.skripsi.view');
				Route::get('/preview/{id}', 'ProdiRequestSkripsiController@previewRequestAttachment')->name('prodi.request.preview_ilmiah');
			});
		});

		// crud dosen user
		Route::prefix('dosen')->group(function() {
			Route::get('', 'ProdiUserController@index')->name('dosen');
			Route::get('create', 'ProdiUserController@create')->name('dosen.create');
			Route::post('create', 'ProdiUserController@store')->name('dosen.create');
			Route::get('edit/{prodiUser}', 'ProdiUserController@edit')->name('dosen.update');
			Route::post('/edit/{prodiUser}', 'ProdiUserController@update')->name('dosen.update');
			Route::delete('destroy/{prodiUser}', 'ProdiUserController@destroy')->name('dosen.destroy');
			// this for assign dosen user that are already created from another prodi :P
			Route::get('assign', 'ProdiUserController@assign')->name('dosen.assign');
			Route::post('assign', 'ProdiUserController@assigning')->name('dosen.assign');
			Route::get('reassign/{prodiAssign}', 'ProdiUserController@editAssign')->name('dosen.reassign');
			Route::post('reassign/{prodiAssign}', 'ProdiUserController@updateAssign')->name('dosen.reassign');
			Route::delete('remove/{prodiAssign}', 'ProdiUserController@destroyAssign')->name('dosen.remove');
		});

		// penjadwalan sidang section
		Route::prefix('penjadwalan')->group(function() {
			// kp section
			Route::prefix('kp')->group(function() {
				Route::get('', 'ProdiPenjadwalanSidangKPController@index')->name('prodi.penjadwalan.kp');
				Route::get('/assign/{penjadwalan}', 'ProdiPenjadwalanSidangKPController@getPenjadwalanForStoreData')->name('prodi.penjadwalan.assign.kp');
				Route::post('/assign/{penjadwalan}', 'ProdiPenjadwalanSidangKPController@storePenjadwalanData')->name('prodi.penjadwalan.assign.kp');
				Route::get('/view_penjadwalan/{penjadwalan}', 'ProdiPenjadwalanSidangKPController@viewPenjadwalan')->name('prodi.penjadwalan.view.kp');
			});

			// skripsi section
			Route::prefix('skripsi')->group(function() {
				Route::get('', 'ProdiPenjadwalanSidangSkripsiController@index')->name('prodi.penjadwalan.skripsi');
				Route::get('/assign/{penjadwalan}', 'ProdiPenjadwalanSidangSkripsiController@getPenjadwalanForStoreData')->name('prodi.penjadwalan.assign.skripsi');
				Route::post('/assign/{penjadwalan}', 'ProdiPenjadwalanSidangSkripsiController@storePenjadwalanData')->name('prodi.penjadwalan.assign.skripsi');
				Route::get('/view_penjadwalan/{penjadwalan}', 'ProdiPenjadwalanSidangSkripsiController@viewPenjadwalan')->name('prodi.penjadwalan.view.skripsi');
			});

			// tesis section
			Route::prefix('tesis')->group(function() {
				Route::get('', 'ProdiPenjadwalanSidangTesisController@index')->name('prodi.penjadwalan.tesis');
				Route::get('/assign/{penjadwalan}', 'ProdiPenjadwalanSidangTesisController@getPenjadwalanForStoreData')->name('prodi.penjadwalan.assign.tesis');
				Route::post('/assign/{penjadwalan}', 'ProdiPenjadwalanSidangTesisController@storePenjadwalanData')->name('prodi.penjadwalan.assign.tesis');
				Route::get('/view_pengesahan/{penjadwalan}', 'ProdiPenjadwalanSidangTesisController@viewPenjadwalan')->name('prodi.penjadwalan.view.tesis');
			});
		});	

		// Berita acara
		Route::prefix('berita_acara')->group(function() {
			// kp section
			Route::prefix('admin')->group(function() {
				Route::prefix('kp')->group(function() {
					Route::get('', 'ProdiAdminBeritaAcaraSidangKPController@index')->name('prodi_admin.berita.acara.kp');
				});		
				
				Route::prefix('skripsi')->group(function() {
					Route::get('', 'ProdiAdminBeritaAcaraSidangSkripsiController@index')->name('prodi_admin.berita.acara.skripsi');
				});

				Route::prefix('tesis')->group(function() {
					Route::get('', 'ProdiAdminBeritaAcaraSidangTesisController@index')->name('prodi_admin.berita.acara.tesis');
				});
			});
		});
	});

	Route::group(['middleware' => 'can:is-prodi-dosen'], function() {
		// Berita acara
		Route::prefix('berita_acara')->group(function() {
			// kp section
			Route::prefix('kp')->group(function() {
				Route::get('', 'ProdiBeritaAcaraSidangKPController@index')->name('prodi.berita.acara.kp');
				Route::get('/form_sidang/{participant}', 'ProdiBeritaAcaraSidangKPController@formSidang')->name('prodi.berita_acara_form.kp');
				Route::post('/form_sidang/{participant}', 'ProdiBeritaAcaraSidangKPController@isiFormSidang')->name('prodi.berita_acara_isi_form.kp');
			});

			// skripsi section
			Route::prefix('skripsi')->group(function() {
				Route::get('', 'ProdiBeritaAcaraSidangSkripsiController@index')->name('prodi.berita.acara.skripsi');
				Route::get('/form_sidang/{participant}', 'ProdiBeritaAcaraSidangSkripsiController@formSidang')->name('prodi.berita_acara_form.skripsi');
				Route::post('/form_sidang/{participant}', 'ProdiBeritaAcaraSidangSkripsiController@isiFormSidang')->name('prodi.berita_acara_isi_form.skripsi');
			});

			// tesis section
			Route::prefix('tesis')->group(function() {
				Route::get('', 'ProdiBeritaAcaraSidangTesisController@index')->name('prodi.berita.acara.tesis');
				Route::get('/form_sidang/{participant}', 'ProdiBeritaAcaraSidangTesisController@formSidang')->name('prodi.berita_acara_form.tesis');
				Route::post('/form_sidang/{participant}', 'ProdiBeritaAcaraSidangTesisController@isiFormSidang')->name('prodi.berita_acara_isi_form.tesis');
			});
		});
	});
});