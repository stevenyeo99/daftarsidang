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
                  <a class="btn btn-box-tool blue-col" href="{{ $back_route }}">
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
                  {{ Form::label('study_program_name', 'Program Studi') }}
                  @if (!empty($study_program))
                    {{ Form::text('study_program_name', $study_program->name, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  @else
                    {{ Form::text('study_program_name', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  @endif
                  <br>
                  {{ Form::label('type', 'Jenis Karya') }}
                  {{ Form::text('type', $request_type, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  <br>
                  {{ Form::label('session', 'Sesi') }}
                  {{ Form::text('session', $request->session, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  <br>
                  {{ Form::label('title', 'Judul') }}
                  {{ Form::text('title', $request->title, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  <br>
                  @if (isset($is_skripsi_or_tesis_request))
                    <div class="form-group">
                      <div class="col-xs-6 p-l-0">
                        {{ Form::label('start_date', 'Tanggal Mulai Bimbingan') }}
                        {{ Form::text('start_date', date('d M Y', strtotime($request->start_date)), array('class' => 'form-control pull-right', 'placeholder' => 'Kosong...', 'disabled')) }}
                      </div>
                      <div class="col-xs-6 p-l-0 p-r-0">
                        {{ Form::label('end_date', 'Tanggal Akhir Bimbingan') }}
                        {{ Form::text('end_date', date('d M Y', strtotime($request->end_date)), array('class' => 'form-control pull-right', 'placeholder' => 'Kosong...', 'disabled')) }}
                      </div>
                    </div>
                    <br/><br/><br/>
                    {{ Form::label('toeic_grade', 'Nilai TOEIC') }}
                    {{ Form::text('toeic_grade', $student->toeic_grade, array('class' => 'numeric-field form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                    <br>
                  @endif
                  
                  @if(isset($is_skripsi_or_tesis_request))
                      @if(!empty($lembar_persetujuan))
                        {{ Form::label('Lembar Persetujuan') }}
                        <br>
                        @if(Gate::allows('is-admin-group'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('baak.request.preview_ilmiah', $lembar_persetujuan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a> 
                        @elseif(Gate::allows('is-prodi-admin'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('prodi.request.preview_ilmiah', $lembar_persetujuan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-finance-group'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('baak.request.preview_ilmiah', $lembar_persetujuan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>   
                        @else
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('student.request.preview_ilmiah', $lembar_persetujuan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>                        
                        @endif
                        <br></br>
                      @endif

                      @if(!empty($kartu_bimbingan))
                        {{ Form::label('Kartu Bimbingan') }}
                        <br>
                        @if(Gate::allows('is-admin-group'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('baak.request.preview_ilmiah', $kartu_bimbingan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a> 
                        @elseif(Gate::allows('is-prodi-admin'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('prodi.request.preview_ilmiah', $kartu_bimbingan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a> 
                        @elseif(Gate::allows('is-finance-group'))
                        <a onclick="" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @else
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('student.request.preview_ilmiah', $kartu_bimbingan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>                        
                        @endif
                        <br></br>
                      @endif

                      @if(!empty($lembar_turnitin))
                        {{ Form::label('Lembar Turnitin') }}
                        <br>
                        @if(Gate::allows('is-admin-group'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('baak.request.preview_ilmiah', $lembar_turnitin->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-prodi-admin'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('prodi.request.preview_ilmiah', $lembar_turnitin->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-finance-group'))
                        <a onclick="" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @else
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('student.request.preview_ilmiah', $lembar_turnitin->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @endif
                        <br></br>
                      @endif

                      @if(!empty($lembar_plagiat))
                        {{ Form::label('Lembar Plagiat') }}
                        <br>
                        @if(Gate::allows('is-admin-group'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('baak.request.preview_ilmiah', $lembar_plagiat->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-prodi-admin'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('prodi.request.preview_ilmiah', $lembar_plagiat->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-finance-group'))
                        <a onclick="" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @else
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('student.request.preview_ilmiah', $lembar_plagiat->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @endif
                        <br></br>
                      @endif

                      @if(!empty($foto_meteor))
                        {{ Form::label('Foto Meteor') }}
                        <br>
                        @if(Gate::allows('is-admin-group'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('baak.request.preview_ilmiah', $foto_meteor->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-prodi-admin'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('prodi.request.preview_ilmiah', $foto_meteor->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-finance-group'))
                        <a onclick="" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @else
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('student.request.preview_ilmiah', $foto_meteor->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @endif
                        <br></br>
                      @endif

                      @if(!empty($official_toeic))
                        {{ Form::label('Official TOEIC') }}
                        <br>
                        @if(Gate::allows('is-admin-group'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('baak.request.preview_ilmiah', $official_toeic->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-prodi-admin'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('prodi.request.preview_ilmiah', $official_toeic->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-finance-group'))
                        <a onclick="" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @else
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('student.request.preview_ilmiah', $official_toeic->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>                        
                        @endif
                        <br></br>
                      @endif

                      @if(!empty($abstract_uclc))
                        {{ Form::label('Abstract UCLC') }}
                        <br>
                        @if(Gate::allows('is-admin-group'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('baak.request.preview_ilmiah', $abstract_uclc->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-prodi-admin'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('prodi.request.preview_ilmiah', $abstract_uclc->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-finance-group'))
                        <a onclick="" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @else
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('student.request.preview_ilmiah', $abstract_uclc->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>                        
                        @endif
                        <br></br>
                      @endif
                  @else
                      @if(!empty($lembar_persetujuan))
                        {{ Form::label('Lembar Persetujuan') }}
                        <br>
                        @if(Gate::allows('is-admin-group'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('baak.request.preview', $lembar_persetujuan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-prodi-admin'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('prodi.request.preview', $lembar_persetujuan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @else
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('student.request.preview', $lembar_persetujuan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @endif
                        <br><br>
                      @endif
                      @if(!empty($kartu_bimbingan))
                        {{ Form::label('Kartu Bimbingan') }}
                        <br>
                        @if(Gate::allows('is-admin-group'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('baak.request.preview', $kartu_bimbingan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-prodi-admin'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('prodi.request.preview', $kartu_bimbingan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @else
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('student.request.preview', $kartu_bimbingan->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @endif
                        <br><br>
                      @endif   
                      @if(!empty($lembar_turnitin))
                        {{ Form::label('Lembar Turnitin') }}
                        <br>
                        @if(Gate::allows('is-admin-group'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('baak.request.preview', $lembar_turnitin->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @elseif(Gate::allows('is-prodi-admin'))
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('prodi.request.preview', $lembar_turnitin->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @else
                        <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('student.request.preview', $lembar_turnitin->id) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
                        @endif
                        <br><br>
                      @endif
                    @endif
                                 
                  {{ Form::label('mentor_name', 'Name Pembimbing') }}
                  {{ Form::text('mentor_name', $request->mentor_name, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  <br>
                  {{ Form::label('status', 'Status') }}
                  {{ Form::text('status', $request_status, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  @if ($request->repeat_reason != null) 
                    <br>
                    {{ Form::label('repeat_reason', 'Alasan Daftar Ulang') }}
                    <span class="text-red">*jika ulang daftar</span>
                    {{ Form::text('repeat_reason', $request->repeat_reason, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'disabled')) }}
                  @endif
                  @if ($request->reject_reason != null) 
                    <br>
                    {{ Form::label('reject_reason', 'Alasan ditolaknya pendaftaran') }}
                    <span class="text-red">*pernah ditolak</span>
                    {{ Form::textarea('reject_reason', $request->reject_reason, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'size' => '30x5', 'disabled')) }}
                  @endif

                  @if(Gate::allows('is-prodi-admin') && isset($is_skripsi_or_tesis_request))
                    <br>
                    {{ Form::label('transkrip', 'Transkrip Nilai') }}
                    <br>
                    <!-- view transkrip nilai -->
                      <a onclick="fnOpenPopUpWindow('previewFile', '{{ route('prodi.request.skripsi.view_transkrip', $request->student()->first()->npm) }}')" class="btn btn-info">
                          <i class="fa fa-eye"></i> Lihat
                        </a>
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
      function fnOpenPopUpWindow(windowName, URL) {
            var availHeight = screen.availHeight;
            var availWidth = screen.availWidth;
            var x = 0, y = 0;
            if (document.all) {
                x = window.screentop;
                y = window.screenLeft;
            } else if (document.layers) {
                x = window.screenX;
                y = window.screenY;
            }
            var windowArguments = 'resizable=1,toolbar=0,location=0,directories=0,addressbar=0,scrollbars=1,status=1,menubar=0,top=0,left=0, ';
            windowArguments += 'screenX=' + x + ',screenY=' + y + ',width=' + availWidth + ',height=' + availHeight;

            var newWindow = window.open(URL, windowName, windowArguments);
            newWindow.moveTo(0, 0);

            return newWindow;
        }
  </script>
@endpush