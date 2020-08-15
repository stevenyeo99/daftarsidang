@extends('layouts.master')

@section('content')
@include('shared.page_header')

<div class="m-b-15 m-l-15 m-r-15 alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info-circle"></i> Pemberitahuan!</h4>
    @if (!Gate::allows('is-student-profile-filled'))
        Silahkan mengisi data profil terlebih dahulu sebelum melanjutkan ke aksi selanjutnya.<br><br>
    @endif

    @if (!isset(Auth::guard('student')->user()->sex) || Auth::guard('student')->user()->sex === null)
        Sepertinya data default jenis kelamin anda tidak terisi, silahkan mengisi jenis kelamin dengan tombol "Perbaiki Data".<br><br>
    @endif

    @if (!isset(Auth::guard('student')->user()->birth_place) || Auth::guard('student')->user()->birth_place === null)
        Sepertinya data default tempat lahir anda tidak terisi, silahkan mengisi tempat lahir dengan tombol "Perbaiki Data".<br><br>
    @endif

    @if (!isset(Auth::guard('student')->user()->birthdate) || Auth::guard('student')->user()->birthdate === null)
        Sepertinya data default tanggal lahir anda tidak terisi, silahkan mengisi tanggal lahir dengan tombol "Perbaiki Data".<br><br>
    @endif

    @if (!isset(Auth::guard('student')->user()->religion) || strlen(Auth::guard('student')->user()->religion) == 0 || Auth::guard('student')->user()->religion === null)
        Sepertinya data default agama anda tidak terisi, silahkan mengisi agama dengan tombol "Perbaiki Data".<br><br>
    @endif

    @if (!isset(Auth::guard('student')->user()->study_program_id) || Auth::guard('student')->user()->study_program_id === null)
        Sepertinya data default program studi anda tidak terisi, silahkan mengisi program studi dengan tombol "Perbaiki Data".<br><br>
    @endif

    @if (!isset(Auth::guard('student')->user()->phone_number) || strlen(Auth::guard('student')->user()->phone_number) == 0 || Auth::guard('student')->user()->phone_number === null)
        Sepertinya data default nomor telefon anda tidak terisi, silahkan mengisi nomor telefon dengan tombol "Perbaiki Data".<br><br>
    @endif

    @if (!isset(Auth::guard('student')->user()->address) || strlen(Auth::guard('student')->user()->address) == 0 || Auth::guard('student')->user()->address === null)
        Sepertinya data default alamat anda tidak terisi, silahkan mengisi alamat dengan tombol "Perbaiki Data".<br><br>
    @endif

    <strong>
        Data ini akan digunakan sebagai acuan untuk pencetakan Ijazah, Transkrip Nilai dan SKPI. UIB tidak bertanggung jawab terhadap kesalahan pencetakan dokumen tersebut karena kesalahan pengisian data dibawah ini.
    </strong>
</div>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                Profil
                <small class="text-red">
                  {{-- these datas could not be controlled on this platform, please go to uib's portal. --}}
                  harap diisi dengan benar.
                </small>
              </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {{ Form::open(array('url' => route('student.profile'), 'class' => 'student-profile-form')) }}
              <div class="box-body">
                <div class="form-group">
                  <div class="col-xs-6 p-l-0">
                    {{ Form::label('npm', 'Npm') }}
                    <span class="text-red">*data default</span>
                    {{ Form::text('npm', Auth::guard('student')->user()->npm, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    <br>

                    {{ Form::label('name', 'Nama') }}
                    <span class="text-red">*data default</span>
                    {{ Form::text('name', Auth::guard('student')->user()->name, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    <br>

                    {{ Form::label('sex', 'Jenis Kelamin') }}
                    <span class="text-red">*data default</span>
                     @if (Auth::guard('student')->user()->is_profile_accurate)
                      {{ Form::select('sex', $genders, Auth::guard('student')->user()->sex, array('class' => 'width-100p form-control select2', 'placeholder' => '', 'disabled')) }}
                     @else
                      {{ Form::select('sex', $genders, Auth::guard('student')->user()->sex, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                     @endif
                    <br><br>

                    {{ Form::label('birth_place', 'Tempat Lahir') }}
                    <span class="text-red">*data default</span>
                     @if (Auth::guard('student')->user()->is_profile_accurate)
                      {{ Form::text('birth_place', Auth::guard('student')->user()->birth_place, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                     @else
                      {{ Form::text('birth_place', Auth::guard('student')->user()->birth_place, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                     @endif
                    <br>

                    {{ Form::label('birthdate', 'Tanggal Lahir') }}
                    <span class="text-red">*data default</span>
                    @if(Auth::guard('student')->user()->birthdate != null) 
                         @if (Auth::guard('student')->user()->is_profile_accurate)
                          {{ Form::text('birthdate', date('d M Y', strtotime(Auth::guard('student')->user()->birthdate)), array('id' => 'datepicker', 'class' => 'datepicker-me-class form-control pull-right', 'placeholder' => 'Kosong...', 'data-date-end-date' => '0d', 'disabled')) }}
                         @else
                          {{ Form::text('birthdate', date('d M Y', strtotime(Auth::guard('student')->user()->birthdate)), array('id' => 'datepicker', 'class' => 'datepicker-me-class form-control pull-right', 'placeholder' => 'Kosong...', 'data-date-end-date' => '0d')) }}
                         @endif
                    @else
                         @if (Auth::guard('student')->user()->is_profile_accurate)
                          {{ Form::text('birthdate', date('d M Y', strtotime('-18 year')), array('id' => 'datepicker', 'class' => 'datepicker-me-class form-control pull-right', 'placeholder' => 'Kosong...', 'data-date-end-date' => '0d', 'disabled')) }}
                         @else
                          {{ Form::text('birthdate', date('d M Y', strtotime('-18 year')), array('id' => 'datepicker', 'class' => 'datepicker-me-class form-control pull-right', 'placeholder' => 'Kosong...', 'data-date-end-date' => '0d')) }}
                         @endif
                    @endif
                    <br>
                    <br>

                    {{ Form::label('religion', 'Agama', array('class' => 'm-t-20')) }}
                    <span class="text-red">*data default</span>
                     @if (Auth::guard('student')->user()->is_profile_accurate)
                      {{ Form::text('religion', Auth::guard('student')->user()->religion, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                     @else
                      {{ Form::text('religion', Auth::guard('student')->user()->religion, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                     @endif
                    <br>

                    {{ Form::label('certification_degree', 'Gelar Sertifikasi') }}
                    <span class="text-red">*hanya untuk gelar sertifikasi</span>
                    {{ Form::text('certification_degree', Auth::guard('student')->user()->certification_degree, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    <br>

                    {{ Form::label('existing_degree', 'Gelar S1') }}
                    <span class="text-red">*hanya untuk mahasiswa magister</span>
                    {{ Form::text('existing_degree', Auth::guard('student')->user()->existing_degree, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    <br>

                    {{ Form::label('address', 'Alamat Domisili') }}
                    <span class="text-red">*data default</span>
                     @if (Auth::guard('student')->user()->is_profile_accurate)
                      {{ Form::textarea('address', Auth::guard('student')->user()->address, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'size' => '30x5', 'disabled')) }}
                     @else
                      {{ Form::textarea('address', Auth::guard('student')->user()->address, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'size' => '30x5')) }}
                     @endif
                  </div>
                  <div class="col-xs-6 p-l-0">
                    {{ Form::label('study_program', 'Program Studi') }}
                    <span class="text-red">*data default</span>
                     @if (Auth::guard('student')->user()->is_profile_accurate)
                      {{ Form::select('study_program', $study_programs, Auth::guard('student')->user()->study_program_id, array('class' => 'width-100p form-control select2', 'placeholder' => '', 'disabled')) }}
                     @else
                      {{ Form::select('study_program', $study_programs, Auth::guard('student')->user()->study_program_id, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                     @endif
                    <br><br>

                    {{ Form::label('phone_number', 'Nomor Telefon') }}
                    <span class="text-red">*data default</span>
                     @if (Auth::guard('student')->user()->is_profile_accurate)
                      {{ Form::text('phone_number', Auth::guard('student')->user()->phone_number, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                     @else
                      {{ Form::text('phone_number', Auth::guard('student')->user()->phone_number, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...')) }}
                     @endif
                    <br>

                    {{ Form::label('NIK', 'NIK (Sesuai KTP)') }}
                    <span class="text-red">*</span>
                    {{ Form::text('NIK', Auth::guard('student')->user()->NIK, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...')) }}
                    <br>

                    {{ Form::label('toeic_grade', 'Nilai TOEIC') }}
                    <span class="text-red">*</span>
                    {{ Form::text('toeic_grade', Auth::guard('student')->user()->toeic_grade, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...')) }}
                    <br>

                    {{ Form::label('semester', 'Semester Pendaftaran Sidang') }}
                    <span class="text-red">*</span>
                    {{ Form::select('semester', $semesters, Auth::guard('student')->user()->semester_id, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                    <br><br>

                    {{ Form::label('email', 'Email') }}
                    <span class="text-red">*</span>
                    {{ Form::text('email', Auth::guard('student')->user()->email, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    <br>

                    {{ Form::label('work_status', 'Status Pekerjaan') }}
                    <span class="text-red">*</span>
                    {{ Form::select('work_status', $work_statuses, Auth::guard('student')->user()->work_status, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                    <br><br>

                    {{ Form::label('toga_size', 'Ukuran Toga') }}
                    <span class="text-red">*</span>
                    {{ Form::select('toga_size', $toga_sizes, Auth::guard('student')->user()->toga_size, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                    <br><br>

                    {{ Form::label('consumption_type', 'Konsumsi saat Wisuda') }}
                    <span class="text-red">*</span>
                    {{ Form::select('consumption_type', $consumption_types, Auth::guard('student')->user()->consumption_type, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                  </div>

                  <div class="col-xs-12 p-l-0 m-t-15">
                      {{ Form::checkbox('agreement_check', 'agreement_check', false, array('onClick' => 'agreementChecked()', 'id' => 'agreement_check', 'class' => 'agreement-checkbox')) }}
                      {{ Form::label('agreement_check', 'Saya menyatakan bahwa data yang sudah saya isi adalah BENAR dan VALID untuk digunakan sebagai acuan data dalam pecetakan Ijazah, Transkrip Nilai dan SKPI', array('class' => 'agreement-checkbox-label')) }}
                  </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                 {{ Form::submit($btn_label, array('class' => 'btn btn-primary', 'id' => 'profile_submit_button', 'disabled')) }}

                 @if (Auth::guard('student')->user()->is_profile_accurate)
                   {{ Form::button("Perbaiki Data", array('class' => 'is-profile-accurate-btn btn btn-warning')) }}
                   <small class="text-info"><i>Tombol <b>Perbaiki Data</b> hanya digunakan ketika ada kesalahan pada <span class="text-red">data default.</span></i></small>
                 @endif
              </div>
            {{ Form::close() }}
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
  <!-- /.row -->

    <div class="row">
        <div class="col-xs-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                Data Orang Tua
                <small class="text-red">
                  *wajib
                </small>
              </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {{ Form::open(array('url' => route('student.parent.profile'))) }}
              <div class="box-body">
                <div class="form-group">
                  {{ Form::label('father_name', 'Nama Ayah Kandung') }}
                  <span class="text-red">*</span>
                  @if (!empty($father))
                    {{ Form::text('father_name', $father->name, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('father_name', null, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @endif

                  <br>
                  {{ Form::label('mother_name', 'Nama Ibu Kandung') }}
                  <span class="text-red">*</span>
                  @if (!empty($mother))
                    {{ Form::text('mother_name', $mother->name, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('mother_name', null, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @endif

                  <h4 class="text-info"><i>jika orangtua bekerja / memiliki usaha</i></h4>
                  <div class="col-xs-6 p-l-0">
                    {{ Form::label('company_name', 'Nama Tempat Usaha') }}
                    <span class="text-red">*</span>
                    @if (!empty($parentCurrentCompany))
                      {{ Form::text('company_name', $parentCurrentCompany->name, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @else
                      {{ Form::text('company_name', null, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @endif
                    <br>
                    {{ Form::label('company_field', 'Bidang Usaha') }}
                    <span class="text-red">*</span>
                    @if (!empty($parentCurrentCompany))
                      {{ Form::text('company_field', $parentCurrentCompany->field, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @else
                      {{ Form::text('company_field', null, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @endif
                    <br>
                  </div>
                  <div class="col-xs-6 p-l-0">
                    {{ Form::label('company_phone_number', 'Nomor Telefon Tempat Usaha') }}
                    @if (!empty($parentCurrentCompany))
                      {{ Form::text('company_phone_number', $parentCurrentCompany->phone_number, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...')) }}
                    @else
                      {{ Form::text('company_phone_number', null, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...')) }}
                    @endif
                    <br>
                    {{ Form::label('company_address', 'Alamat Tempat Usaha') }}
                    <span class="text-red">*</span>
                    @if (!empty($parentCurrentCompany))
                      {{ Form::text('company_address', $parentCurrentCompany->address, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @else
                      {{ Form::text('company_address', null, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @endif
                  </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                 {{ Form::submit($btn_label, array('class' => 'btn btn-primary')) }}
              </div>
              {{ Form::close() }}
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
  <!-- /.row -->

    <div class="row">
        <div class="col-xs-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                Data Tempat Usaha
                <small class="text-info">
                  hanya diisi jika bekerja / memiliki usaha
                </small>
              </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {{ Form::open(array('url' => route('student.company.profile'))) }}
              <div class="box-body">
                <div class="form-group">
                  {{-- <div class="col-xs-6 p-l-0"> --}}
                    {{ Form::label('company_name', 'Nama Tempat Usaha') }}
                    <span class="text-red">*</span>
                    @if (!empty(Auth::guard('student')->user()->company()->first()))
                      {{ Form::text('company_name', Auth::guard('student')->user()->company()->first()->name, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @else
                      {{ Form::text('company_name', null, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @endif
                    <br>
                    {{ Form::label('company_field', 'Bidang Usaha') }}
                    <span class="text-red">*</span>
                    @if (!empty(Auth::guard('student')->user()->company()->first()))
                      {{ Form::text('company_field', Auth::guard('student')->user()->company()->first()->field, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @else
                      {{ Form::text('company_field', null, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @endif
                    <br>
                  {{-- </div> --}}
                  {{-- <div class="col-xs-6 p-l-0"> --}}
                    {{ Form::label('company_phone_number', 'Nomor Telefon Tempat Usaha') }}
                    @if (!empty(Auth::guard('student')->user()->company()->first()))
                      {{ Form::text('company_phone_number', Auth::guard('student')->user()->company()->first()->phone_number, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...')) }}
                    @else
                      {{ Form::text('company_phone_number', null, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...')) }}
                    @endif
                    <br>
                    {{ Form::label('company_address', 'Alamat Tempat Usaha') }}
                    <span class="text-red">*</span>
                    @if (!empty(Auth::guard('student')->user()->company()->first()))
                      {{ Form::text('company_address', Auth::guard('student')->user()->company()->first()->address, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @else
                      {{ Form::text('company_address', null, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @endif
                  {{-- </div> --}}
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                 {{ Form::submit($btn_label, array('class' => 'btn btn-primary')) }}
              </div>
              {{ Form::close() }}
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
  <!-- /.row -->
</section>
@endsection

@push('custom_js')
    <script type="text/javascript">
        $(document).ready(function(){
            // For A Delete Record Popup
            $(document).on('click', '.is-profile-accurate-btn', function () {
                $('body').find('.student-profile-form').append('<input name="is_profile_accurate_value" type="hidden" value="false">');
                $('body').find('.student-profile-form').submit();
            });
        });

        function agreementChecked() {
          var agreementCheckBox = document.getElementById('agreement_check');

          if (agreementCheckBox.checked) {
            $('#profile_submit_button').prop('disabled', false);
            $('#profile_submit_button').removeClass('disabled');
          } else {
            $('#profile_submit_button').prop('disabled', true);
            $('#profile_submit_button').addClass('disabled');
          }
        }
    </script>
@endpush