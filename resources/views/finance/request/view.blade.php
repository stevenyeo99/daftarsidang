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
              <h3 class="box-title">Rincian</h3>

              <div class="box-tools pull-right">
                {{-- <button type="button" class="btn btn-box-tool"> --}}
                  <a class="btn btn-box-tool blue-col" href="{{ route('finance.request.skripsi') }}">
                    <i class="fa fa-arrow-left"></i>
                    Kembali
                  </a>
                {{-- </button> --}}
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              <div class="box-body">
                <div class="form-group">
                  {{ Form::label('npm', 'NPM') }}
                  {{ Form::text('npm', $student->npm, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  <br>
                  {{ Form::label('name', 'Nama') }}
                  {{ Form::text('name', $student->name, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  <br>
                  {{ Form::label('angkatan', 'Angkatan') }}
                  {{ Form::text('angkatan', $angkatan, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  <br>
                  {{ Form::label('study_program_name', 'Program Studi') }}
                  @if (!empty($study_program))
                    {{ Form::text('study_program_name', $study_program->name, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  @else
                    {{ Form::text('study_program_name', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  @endif
                <br>
                  {{ Form::label('status', 'Status') }}
                  {{ Form::text('status', $request_status, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  <br>
                  {{ Form::label('email', 'Email') }}
                  {{ Form::text('email', $student->email, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  @if ($request->repeat_reason != null) 
                    <br>
                    {{ Form::label('repeat_reason', 'Alasan Daftar Ulang') }}
                    <span class="text-red">*jika ulang daftar</span>
                    {{ Form::text('repeat_reason', $request->repeat_reason, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  @endif
                  @if ($request->reject_reason != null) 
                    <br>
                    {{ Form::label('reject_reason', 'Alasan tervalidasi gagal keuangan') }}
                    <span class="text-red">*pernah ditolak</span>
                    {{ Form::textarea('reject_reason', $request->reject_reason, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'size' => '30x5', 'disabled')) }}
                  @endif
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
      
  </script>
@endpush