@extends('layouts.master')

@section('content')
@include('shared.page_header')

<div class="m-b-15 m-l-15 m-r-15 alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info-circle"></i> Pemberitahuan!</h4>
    @if (Gate::allows('is-student-must-fill-attachment'))
        Dikarenakan anda mengubah data penting seperti TTL atau jenis kelamin, maka anda diwajibkan untuk lampirkan Ijazah SMA, KTP, KK dan AK.<br><br>
    @endif

    <strong>
        Dokumen yang diupload harus hasil scan sehingga mudah divalidasi.
    </strong>
</div>
<!-- Main content -->
<section class="content">
    {{-- <div class="row">
        <div class="col-md-6">
            <div class="box box-primary h-500px">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Scan KTP
                        @if ($ktp == null) 
                            <span class="text-red">*belum terisi</span>
                        @endif
                    </h3>

                  <div class="box-tools pull-right">
                    <div class="button-top-upload-file">
                        {{ Form::open(array('url' => route('student.attachment.upload.ktp'), 'files' => true)) }}
                            {!! Html::decode(Form::label('ktpUploader', '<i class="fa fa-upload"></i> Unggah', ['class' => 'btn btn-box-tool'])) !!}
                            {{ Form::file('ktpUploader', array('id'=>'ktpUploader', 'class'=>'hide', 'onchange' => 'this.form.submit()')) }}
                        {{ Form::close() }}
                    </div>
                  </div>
                </div>
                <!-- /.box-header -->
                @if ($ktp != null)
                    <div class="box-body box-has-image" style="background: url('/images/{{$ktp->file_name}}');">
                    </div>
                @else
                    <div class="box-body box-has-image" style="background: url(../assets/img/empty_state.png); background-size: 50% !important;">
                    </div>
                @endif
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-6">
            <div class="box box-primary h-500px">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Scan KK
                        @if ($kartuKeluarga == null) 
                            <span class="text-red">*belum terisi</span>
                        @endif
                    </h3>

                  <div class="box-tools pull-right">
                    <div class="button-top-upload-file">
                        {{ Form::open(array('url' => route('student.attachment.upload.kk'), 'files' => true)) }}
                            {!! Html::decode(Form::label('kartuKeluargaUploader', '<i class="fa fa-upload"></i> Unggah', ['class' => 'btn btn-box-tool'])) !!}
                            {{ Form::file('kartuKeluargaUploader', array('id'=>'kartuKeluargaUploader', 'class'=>'hide', 'onchange' => 'this.form.submit()')) }}
                        {{ Form::close() }}
                    </div>
                  </div>
                </div>
                <!-- /.box-header -->
                @if ($kartuKeluarga != null)
                    <div class="box-body box-has-image" style="background: url('/images/{{$kartuKeluarga->file_name}}');">
                    </div>
                @else
                    <div class="box-body box-has-image" style="background: url(../assets/img/empty_state.png); background-size: 50% !important;">
                    </div>
                @endif
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-6">
            <div class="box box-primary h-500px">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Ijazah SMA
                        @if ($ijazahSMA == null) 
                            <span class="text-red">*belum terisi</span>
                        @endif
                        <span class="text-red">(mahasiswa sarjana)</span>
                    </h3>

                  <div class="box-tools pull-right">
                    <div class="button-top-upload-file">
                        {{ Form::open(array('url' => route('student.attachment.upload.ijazah.sma'), 'files' => true)) }}
                            {!! Html::decode(Form::label('ijazahSMAUploader', '<i class="fa fa-upload"></i> Unggah', ['class' => 'btn btn-box-tool'])) !!}
                            {{ Form::file('ijazahSMAUploader', array('id'=>'ijazahSMAUploader', 'class'=>'hide', 'onchange' => 'this.form.submit()')) }}
                        {{ Form::close() }}
                    </div>
                  </div>
                </div>
                <!-- /.box-header -->
                @if ($ijazahSMA != null)
                    <div class="box-body box-has-image" style="background: url('/images/{{$ijazahSMA->file_name}}');">
                    </div>
                @else
                    <div class="box-body box-has-image" style="background: url(../assets/img/empty_state.png); background-size: 50% !important;">
                    </div>
                @endif
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-6">
            <div class="box box-primary h-500px">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Ijazah S1
                        @if ($ijazahS1 == null) 
                            <span class="text-red">*belum terisi</span>
                        @endif
                        <span class="text-red">(mahasiswa magister)</span>
                    </h3>

                  <div class="box-tools pull-right">
                    <div class="button-top-upload-file">
                        {{ Form::open(array('url' => route('student.attachment.upload.ijazah.s1'), 'files' => true)) }}
                            {!! Html::decode(Form::label('ijazahS1Uploader', '<i class="fa fa-upload"></i> Unggah', ['class' => 'btn btn-box-tool'])) !!}
                            {{ Form::file('ijazahS1Uploader', array('id'=>'ijazahS1Uploader', 'class'=>'hide', 'onchange' => 'this.form.submit()')) }}
                        {{ Form::close() }}
                    </div>
                  </div>
                </div>
                <!-- /.box-header -->
                @if ($ijazahS1 != null)
                    <div class="box-body box-has-image" style="background: url('/images/{{$ijazahS1->file_name}}');">
                    </div>
                @else
                    <div class="box-body box-has-image" style="background: url(../assets/img/empty_state.png); background-size: 50% !important;">
                    </div>
                @endif
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
  <!-- /.row --> --}}
  <div class="row">
      <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="attachment_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="30%">Nama Lampiran</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="40%">Status</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="30%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        KTP
                                    </td>
                                    <td>
                                        @if ($ktp == null) 
                                            <span class="label label-danger">belum terisi </span>
                                        @else
                                            <span class="label label-success">telah terisi [ {{ $ktp->file_name }} ]</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ Form::open(array('url' => route('student.attachment.upload.ktp'), 'files' => true, 'class' => 'dis-inl-block' )) }}
                                            {!! Html::decode(Form::label('ktpUploader', '<i class="fa fa-upload"></i> Unggah', ['class' => 'btn btn-primary'])) !!}
                                            {{ Form::file('ktpUploader', array('id'=>'ktpUploader', 'class'=>'hide', 'onchange' => 'this.form.submit()')) }}
                                        {{ Form::close() }}
                                        
                                        @if($ktp != null)
                                            <a class="btn btn-warning" href="{{ route('student.attachment.download.ktp') }}" title="KTP">
                                                <i class="fa fa-download"></i> Unduh
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Kartu Keluarga
                                    </td>
                                    <td>
                                        @if ($kartuKeluarga == null) 
                                            <span class="label label-danger">belum terisi</span>
                                        @else
                                            <span class="label label-success">telah terisi [ {{ $kartuKeluarga->file_name }} ]</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ Form::open(array('url' => route('student.attachment.upload.kk'), 'files' => true, 'class' => 'dis-inl-block' )) }}
                                            {!! Html::decode(Form::label('kartuKeluargaUploader', '<i class="fa fa-upload"></i> Unggah', ['class' => 'btn btn-primary'])) !!}
                                            {{ Form::file('kartuKeluargaUploader', array('id'=>'kartuKeluargaUploader', 'class'=>'hide', 'onchange' => 'this.form.submit()')) }}
                                        {{ Form::close() }}
                                        
                                        @if($kartuKeluarga != null)
                                            <a class="btn btn-warning" href="{{ route('student.attachment.download.kk') }}" title="Kartu Keluarga">
                                                <i class="fa fa-download"></i> Unduh
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Akta Kelahiran
                                    </td>
                                    <td>
                                        @if ($aktaKelahiran == null) 
                                            <span class="label label-danger">belum terisi</span>
                                        @else
                                            <span class="label label-success">telah terisi [ {{ $aktaKelahiran->file_name }} ]</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ Form::open(array('url' => route('student.attachment.upload.ak'), 'files' => true, 'class' => 'dis-inl-block' )) }}
                                            {!! Html::decode(Form::label('aktaKelahiranUploader', '<i class="fa fa-upload"></i> Unggah', ['class' => 'btn btn-primary'])) !!}
                                            {{ Form::file('aktaKelahiranUploader', array('id'=>'aktaKelahiranUploader', 'class'=>'hide', 'onchange' => 'this.form.submit()')) }}
                                        {{ Form::close() }}
                                        
                                        @if($aktaKelahiran != null)
                                            <a class="btn btn-warning" href="{{ route('student.attachment.download.ak') }}" title="Akta Kelahiran">
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
                                    <td>
                                        @if ($ijazahSMA == null) 
                                            <span class="label label-danger">belum terisi</span>
                                        @else
                                            <span class="label label-success">telah terisi [ {{ $ijazahSMA->file_name }} ]</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ Form::open(array('url' => route('student.attachment.upload.ijazah.sma'), 'files' => true, 'class' => 'dis-inl-block' )) }}
                                            {!! Html::decode(Form::label('ijazahSMAUploader', '<i class="fa fa-upload"></i> Unggah', ['class' => 'btn btn-primary'])) !!}
                                            {{ Form::file('ijazahSMAUploader', array('id'=>'ijazahSMAUploader', 'class'=>'hide', 'onchange' => 'this.form.submit()')) }}
                                        {{ Form::close() }}
                                        
                                        @if($ijazahSMA != null)
                                            <a class="btn btn-warning" href="{{ route('student.attachment.download.ijazah.sma') }}" title="Ijazah SMA">
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
                                    <td>
                                        @if ($ijazahS1 == null) 
                                            <span class="label label-danger">belum terisi</span>
                                        @else
                                            <span class="label label-success">telah terisi [ {{ $ijazahS1->file_name }} ]</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ Form::open(array('url' => route('student.attachment.upload.ijazah.s1'), 'files' => true, 'class' => 'dis-inl-block' )) }}
                                            {!! Html::decode(Form::label('ijazahS1Uploader', '<i class="fa fa-upload"></i> Unggah', ['class' => 'btn btn-primary'])) !!}
                                            {{ Form::file('ijazahS1Uploader', array('id'=>'ijazahS1Uploader', 'class'=>'hide', 'onchange' => 'this.form.submit()')) }}
                                        {{ Form::close() }}
                                        
                                        @if($ijazahS1 != null)
                                            <a class="btn btn-warning" href="{{ route('student.attachment.download.ijazah.s1') }}" title="Ijazah S1">
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
        });

    </script>
@endpush
