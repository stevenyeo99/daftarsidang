@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                Data Profil
              </h3>

              <div class="box-tools pull-right">
                {{-- <button type="button" class="btn btn-box-tool"> --}}
                  <a class="btn btn-box-tool blue-col" href="{{ route('students') }}">
                    <i class="fa fa-arrow-left"></i>
                    Kembali
                  </a>
                {{-- </button> --}}
              </div>
            </div>
            <!-- /.box-header -->
              <div class="box-body">
                <div class="form-group">
                  <div class="col-xs-6 p-l-0">
                    {{ Form::label('npm', 'NPM') }}
                    <span class="text-red">*</span>
                    {{ Form::text('npm', $student->npm, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    <br>

                    {{ Form::label('name', 'Nama') }}
                    <span class="text-red">*</span>
                    {{ Form::text('name', $student->name, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    <br>

                    {{ Form::label('gender', 'Jenis Kelamin') }}
                    <span class="text-red">*</span>
                    {{ Form::select('gender', $genders, $student->sex, array('class' => 'width-100p form-control select2', 'placeholder' => '', 'disabled')) }}
                    <br><br>

                    {{ Form::label('birth_place', 'Tempat Lahir') }}
                    <span class="text-red">*</span>
                    {{ Form::text('birth_place', $student->birth_place, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    <br>

                    {{ Form::label('birthdate', 'Tanggal Lahir') }}
                    <span class="text-red">*</span>
                    {{ Form::text('birthdate', date('d-m-Y', strtotime($student->birthdate)), array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    <br>

                    {{ Form::label('religion', 'Agama') }}
                    <span class="text-red">*</span>
                    {{ Form::text('religion', $student->religion, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    <br>

                    {{ Form::label('certification_degree', 'Gelar Sertifikasi') }}
                    <span class="text-red">*hanya untuk gelar sertifikasi</span>
                    {{ Form::text('certification_degree', $student->certification_degree, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'size' => '30x5', 'disabled')) }}
                    <br>

                    {{ Form::label('existing_degree', 'Gelar S1') }}
                    <span class="text-red">*hanya untuk mahasiswa magister</span>
                    {{ Form::text('existing_degree', $student->existing_degree, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'size' => '30x5', 'disabled')) }}
                    <br>

                    {{ Form::label('address', 'Alamat Domisili') }}
                    <span class="text-red">*</span>
                    {{ Form::textarea('address', $student->address, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'size' => '30x5', 'disabled')) }}
                  </div>
                  <div class="col-xs-6 p-l-0">
                    {{ Form::label('study_program', 'Program Studi') }}
                    <span class="text-red">*</span>
                    {{ Form::select('study_program', $study_programs, $student->study_program_id, array('class' => 'width-100p form-control select2', 'placeholder' => '', 'disabled')) }}
                    <br><br>

                    {{ Form::label('phone_number', 'Nomor Telefon') }}
                    <span class="text-red">*</span>
                    {{ Form::text('phone_number', $student->phone_number, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    <br>

                    {{ Form::label('NIK', 'NIK') }}
                    <span class="text-red">*</span>
                    {{ Form::text('NIK', $student->NIK, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    <br>

                    {{ Form::label('toeic_grade', 'Nilai TOEIC') }}
                    <span class="text-red">*</span>
                    {{ Form::text('toeic_grade', $student->toeic_grade, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    <br>
                    
                    {{ Form::label('semester', 'Semester Pendaftaran Sidang') }}
                    <span class="text-red">*</span>
                    {{ Form::select('semester', $semesters, $student->semester_id, array('class' => 'width-100p form-control select2', 'placeholder' => '', 'disabled')) }}
                    <br><br>

                    {{ Form::label('email', 'Email') }}
                    <span class="text-red">*</span>
                    {{ Form::text('email', $student->email, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    <br>

                    {{ Form::label('work_status', 'Status Pekerjaan') }}
                    <span class="text-red">*</span>
                    {{ Form::select('work_status', $work_statuses, $student->work_status, array('class' => 'width-100p form-control select2', 'placeholder' => '', 'disabled')) }}
                    <br><br>

                    {{ Form::label('toga_size', 'Ukuran Toga') }}
                    <span class="text-red">*</span>
                    {{ Form::select('toga_size', $toga_sizes, $student->toga_size, array('class' => 'width-100p form-control select2', 'placeholder' => '', 'disabled')) }}
                    <br><br>

                    {{ Form::label('consumption_type', 'Konsumsi saat Wisuda') }}
                    <span class="text-red">*</span>
                    {{ Form::select('consumption_type', $consumption_types, $student->consumption_type, array('class' => 'width-100p form-control select2', 'placeholder' => '', 'disabled')) }}
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                Data Orang Tua
              </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {{-- {{ Form::open(array('url' => route('student.parent.profile'))) }} --}}
              <div class="box-body">
                <div class="form-group">
                  {{ Form::label('father_name', 'Nama Ayah Kandung') }}
                  <span class="text-red">*</span>
                  @if (!empty($father))
                    {{ Form::text('father_name', $father->name, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  @else
                    {{ Form::text('father_name', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  @endif

                  <br>
                  {{ Form::label('mother_name', 'Nama Ibu Kandung') }}
                  <span class="text-red">*</span>
                  @if (!empty($mother))
                    {{ Form::text('mother_name', $mother->name, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  @else
                    {{ Form::text('mother_name', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  @endif

                  <h4 class="text-info"><i>Data Pekerjaan Orang Tua</i></h4>
                  <div class="col-xs-6 p-l-0">
                    {{ Form::label('company_name', 'Nama Tempat Usaha') }}
                    <span class="text-red">*</span>
                    @if (!empty($parentCurrentCompany))
                      {{ Form::text('company_name', $parentCurrentCompany->name, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @else
                      {{ Form::text('company_name', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @endif
                    <br>
                    {{ Form::label('company_field', 'Bidang Usaha') }}
                    <span class="text-red">*</span>
                    @if (!empty($parentCurrentCompany))
                      {{ Form::text('company_field', $parentCurrentCompany->field, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @else
                      {{ Form::text('company_field', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @endif
                    <br>
                  </div>
                  <div class="col-xs-6 p-l-0">
                    {{ Form::label('company_phone_number', 'Nomor Telefon Tempat Usaha') }}
                    @if (!empty($parentCurrentCompany))
                      {{ Form::text('company_phone_number', $parentCurrentCompany->phone_number, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @else
                      {{ Form::text('company_phone_number', null, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @endif
                    <br>
                    {{ Form::label('company_address', 'Alamat Tempat Usaha') }}
                    <span class="text-red">*</span>
                    @if (!empty($parentCurrentCompany))
                      {{ Form::text('company_address', $parentCurrentCompany->address, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @else
                      {{ Form::text('company_address', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @endif
                  </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                 {{-- {{ Form::submit($btn_label, array('class' => 'btn btn-primary')) }} --}}
              </div>
              {{-- {{ Form::close() }} --}}
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                Data Tempat Usaha
              </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {{-- {{ Form::open(array('url' => route('student.company.profile'))) }} --}}
              <div class="box-body">
                <div class="form-group">
                  {{-- <div class="col-xs-6 p-l-0"> --}}
                    {{ Form::label('company_name', 'Nama Tempat Usaha') }}
                    <span class="text-red">*</span>
                    @if (!empty($student->company()->first()))
                      {{ Form::text('company_name', $student->company()->first()->name, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @else
                      {{ Form::text('company_name', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @endif
                    <br>
                    {{ Form::label('company_field', 'Bidang Usaha') }}
                    <span class="text-red">*</span>
                    @if (!empty($student->company()->first()))
                      {{ Form::text('company_field', $student->company()->first()->field, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @else
                      {{ Form::text('company_field', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @endif
                    <br>
                  {{-- </div> --}}
                  {{-- <div class="col-xs-6 p-l-0"> --}}
                    {{ Form::label('company_phone_number', 'Nomor Telefon Tempat Usaha') }}
                    @if (!empty($student->company()->first()))
                      {{ Form::text('company_phone_number', $student->company()->first()->phone_number, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @else
                      {{ Form::text('company_phone_number', null, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @endif
                    <br>
                    {{ Form::label('company_address', 'Alamat Tempat Usaha') }}
                    <span class="text-red">*</span>
                    @if (!empty($student->company()->first()))
                      {{ Form::text('company_address', $student->company()->first()->address, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @else
                      {{ Form::text('company_address', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    @endif
                  {{-- </div> --}}
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                 {{-- {{ Form::submit($btn_label, array('class' => 'btn btn-primary')) }} --}}
              </div>
              {{-- {{ Form::close() }} --}}
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                Sertifikasi
              </h3>
            </div>
            <!-- /.box-header -->
              <div class="box-body">
                <div class="table-responsive">
                    <table id="certificate_datatable" class="data-table table table-bordered table-hover">
                        <thead>
                            <tr role="row">
                                <th class="text-center vcenter" rowspan="1" colspan="1">Nama</th>
                                <th class="text-center vcenter" rowspan="1" colspan="1">Tempat</th>
                                <th class="text-center vcenter" rowspan="1" colspan="1">Tahun</th>
                            </tr>
                            <tr role="row" class="filter">
                                <td>
                                    {{ Form::text('name', null, ['id' => 'name', 'class' => 'width-100p form-control form-filter input-sm certificate-search-input-text', 'data-column' => '0']) }}
                                </td>
                                <td>
                                    {{ Form::text('place', null, ['id' => 'place', 'class' => 'width-100p form-control form-filter input-sm certificate-search-input-text', 'data-column' => '1']) }}
                                </td>
                                <td>
                                    {{ Form::text('year', null, ['id' => 'year', 'class' => 'width-100p form-control form-filter input-sm certificate-search-input-text', 'data-column' => '2']) }}
                                </td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
              </div>
              <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                Prestasi
              </h3>
            </div>
            <!-- /.box-header -->
              <div class="box-body">
                <div class="table-responsive">
                    <table id="achievement_datatable" class="data-table table table-bordered table-hover">
                        <thead>
                            <tr role="row">
                                <th class="text-center vcenter" rowspan="1" colspan="1">Nama</th>
                                <th class="text-center vcenter" rowspan="1" colspan="1">Tempat</th>
                                <th class="text-center vcenter" rowspan="1" colspan="1">Tahun</th>
                            </tr>
                            <tr role="row" class="filter">
                                <td>
                                    {{ Form::text('name', null, ['id' => 'name', 'class' => 'width-100p form-control form-filter input-sm achievement-search-input-text', 'data-column' => '0']) }}
                                </td>
                                <td>
                                    {{ Form::text('place', null, ['id' => 'place', 'class' => 'width-100p form-control form-filter input-sm achievement-search-input-text', 'data-column' => '1']) }}
                                </td>
                                <td>
                                    {{ Form::text('year', null, ['id' => 'year', 'class' => 'width-100p form-control form-filter input-sm achievement-search-input-text', 'data-column' => '2']) }}
                                </td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
              </div>
              <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row">
      <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">
                    Daftar Lampiran
                  </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="attachment_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nama Lampiran</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Status</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        KTP
                                    </td>
                                    <td class="custom-text-ellipsis">
                                        @if ($ktp == null) 
                                            <span class="label label-danger">belum terisi </span>
                                        @else
                                            <span class="label label-success">telah terisi [ {{ $ktp->file_name }} ]</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ktp != null)
                                            <a class="btn btn-warning" href="{{ route('admin.student.attachment.download.ktp', $student->id) }}" title="KTP">
                                                <i class="fa fa-download"></i> Unduh
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Kartu Keluarga
                                    </td>
                                    <td class="custom-text-ellipsis">
                                        @if ($kartuKeluarga == null) 
                                            <span class="label label-danger">belum terisi</span>
                                        @else
                                            <span class="label label-success">telah terisi [ {{ $kartuKeluarga->file_name }} ]</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($kartuKeluarga != null)
                                            <a class="btn btn-warning" href="{{ route('admin.student.attachment.download.kk', $student->id) }}" title="Kartu Keluarga">
                                                <i class="fa fa-download"></i> Unduh
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Akta Kelahiran
                                    </td>
                                    <td class="custom-text-ellipsis">
                                        @if ($aktaKelahiran == null) 
                                            <span class="label label-danger">belum terisi</span>
                                        @else
                                            <span class="label label-success">telah terisi [ {{ $aktaKelahiran->file_name }} ]</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($aktaKelahiran != null)
                                            <a class="btn btn-warning" href="{{ route('admin.student.attachment.download.ak', $student->id) }}" title="Akta Kelahiran">
                                                <i class="fa fa-download"></i> Unduh
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Ijazah SMA
                                        <span class="text-red">(mahasiswa sarjana)</span>
                                    </td>
                                    <td class="custom-text-ellipsis">
                                        @if ($ijazahSMA == null) 
                                            <span class="label label-danger">belum terisi</span>
                                        @else
                                            <span class="label label-success">telah terisi [ {{ $ijazahSMA->file_name }} ]</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ijazahSMA != null)
                                            <a class="btn btn-warning" href="{{ route('admin.student.attachment.download.ijazah.sma', $student->id) }}" title="Ijazah SMA">
                                                <i class="fa fa-download"></i> Unduh
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Ijazah S1
                                        <span class="text-red">(mahasiswa magister)</span>
                                    </td>
                                    <td class="custom-text-ellipsis">
                                        @if ($ijazahS1 == null) 
                                            <span class="label label-danger">belum terisi</span>
                                        @else
                                            <span class="label label-success">telah terisi [ {{ $ijazahS1->file_name }} ]</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ijazahS1 != null)
                                            <a class="btn btn-warning" href="{{ route('admin.student.attachment.download.ijazah.s1', $student->id) }}" title="Ijazah S1">
                                                <i class="fa fa-download"></i> Unduh
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row">
      <div class="col-xs-12">
          <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">
                  Status Sidang
                </h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div class="table-responsive">
                    <table id="session_statuses_datatable" class="data-table table table-bordered table-hover">
                        <thead>
                            <tr role="row">
                                <th class="text-center vcenter" rowspan="1" colspan="1">Jenis Karya Ilmiah</th>
                                <th class="text-center vcenter" rowspan="1" colspan="1">Status Sidang</th>
                                <th class="text-center vcenter" rowspan="1" colspan="1">Tanggal Sidang</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
              </div>
            <!-- /.box-body -->
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
        $(document).ready(function() {
            var attachmentDatatable = $('#attachment_datatable').DataTable(
            {
                'autoWidth' : false,
                orderCellsTop: false,
                responsive: false,
                iDisplayLength: 10,
                paging: false,
                info: false,
                language: {
                    'url': "/assets/json/datatable-id-lang.json"
                }
            });

            var certificateDatatable = $('#certificate_datatable').DataTable(
            {
                'autoWidth' : false,
                orderCellsTop: true,
                responsive: false,
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                paging: false,
                info: false,
                language: {
                    'url': "/assets/json/datatable-id-lang.json"
                },
                ajax: {
                    url:  '{{ route('admin.student.certificate.list', $student->id) }}',
                    data: function(data) {
                         data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'name', name: 'name', "width": "120px" },
                    { data: 'place', name: 'place', "width": "160px" },
                    { data: 'year', name: 'year', "width": "80px" },
                ],
            });

            $('.certificate-search-input-text').on( 'keyup click', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
                var v =$(this).val();  // getting search input value
                certificateDatatable.columns(i).search(v).draw();
            } );


            var achievementDatatable = $('#achievement_datatable').DataTable(
            {
                'autoWidth' : false,
                orderCellsTop: true,
                responsive: false,
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                paging: false,
                info: false,
                language: {
                    'url': "/assets/json/datatable-id-lang.json"
                },
                ajax: {
                    url:  '{{ route('admin.student.achievement.list', $student->id) }}',
                    data: function(data) {
                         data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'name', name: 'name', "width": "120px" },
                    { data: 'place', name: 'place', "width": "160px" },
                    { data: 'year', name: 'year', "width": "80px" },
                ],
            });

            $('.achievement-search-input-text').on( 'keyup click', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
                var v =$(this).val();  // getting search input value
                achievementDatatable.columns(i).search(v).draw();
            } );


            var sessionStatusDatatable = $('#session_statuses_datatable').DataTable(
            {
                'autoWidth' : false,
                orderCellsTop: true,
                responsive: false,
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                paging: false,
                info: false,
                language: {
                    'url': "/assets/json/datatable-id-lang.json"
                },
                ajax: {
                    url:  '{{ route('admin.student.session.status.list', $student->id) }}',
                    data: function(data) {
                         data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'type', name: 'type', "width": "120px" },
                    { data: 'status', name: 'status', "width": "160px" },
                    { data: 'date', name: 'date', "width": "80px" },
                ],
            });
        });

    </script>
@endpush