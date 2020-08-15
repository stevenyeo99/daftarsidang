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
              <h3 class="box-title">Formulir</h3>

              <div class="box-tools pull-right">
                {{-- <button type="button" class="btn btn-box-tool"> --}}
                  <a class="btn btn-box-tool blue-col" href="{{ route('student.request.kp') }}">
                    <i class="fa fa-arrow-left"></i>
                    Kembali
                  </a>
                {{-- </button> --}}
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if (!empty($customRequest))
              {{ Form::open(array('id' => 'frm', 'url' => route('student.request.update', $customRequest->id), 'enctype' => 'multipart/form-data')) }}
            @else
              {{ Form::open(array('id' => 'frm', 'url' => route('student.request.create'), 'enctype' => 'multipart/form-data')) }}
            @endif
              <div class="box-body">
                <div class="form-group">
                  {{ Form::label('session', 'Sesi') }}
                  <span class="text-red">*</span>
                  @if (!empty($customRequest))
                    {{ Form::select('session', $sessions, $customRequest->session, array('class' => 'width-100p form-control select2 session-selector', 'placeholder' => '')) }}
                  @else
                    {{ Form::select('session', $sessions, null, array('class' => 'width-100p form-control select2 session-selector', 'placeholder' => '')) }}
                  @endif
                  <br><br>
                  {{-- {{ Form::label('type', 'Type') }}
                  <span class="text-red">*</span>
                  @if (!empty($customRequest))
                    {{ Form::select('type', $types, $customRequest->type, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                  @else
                    {{ Form::select('type', $types, null, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                  @endif
                  <br><br> --}}
                  {{ Form::label('title', 'Judul') }}
                  <span class="text-red">*</span>
                  @if (!empty($customRequest))
                    {{ Form::text('title', $customRequest->title, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('title', Input::old('title'), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @endif
                  <br>
                  {{ Form::label('mentor_name', 'Nama Pembimbing') }}
                  <span class="text-red">*</span>
                  @if (!empty($customRequest))
                    {{ Form::select('mentor_id', $prodi_user, $customRequest->mentor_id, array('id' => 'mentor_id', 'class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                    {{ Form::text('mentor_name', $customRequest->mentor_name, array('id' => 'mentor_name', 'readonly' => true, 'hidden' => true)) }}
                  @else
                    {{ Form::select('mentor_id', $prodi_user, null, array('id' => 'mentor_id', 'class' => 'width-100p form-control select2', 'placeholder' => ''))}}
                    {{ Form::text('mentor_name', Input::old('mentor_name'), array('id' => 'mentor_name', 'readonly' => true, 'hidden' => true)) }}
                  @endif
                  
                  <br><br>

                  {{ Form::label('lembar_persetujuan', 'Lembar Persetujuan') }}
                  <span class="text-red">*</span>
                  @if(!empty($customRequest))
                    <div>
                      <input type="file" id="lembar_persetujuan" name="lembar_persetujuan" style="display: none;">
                      <a class="btn btn-primary" onclick="fnOpenPopUpWindow('preview file', '{{ route('student.request.preview',  $lembar_persetujuan->id) }}')" id="anklePersetujuan">
                        {{$lembar_persetujuan->file_display_name }}
                      </a>
                      &nbsp;
                      <input type="checkbox" id="changeFile" style="cursor: pointer;">
                      <label for="changeFile" id="fileLabel" style="vertical-align: middle; cursor: pointer;" data-upload="no">
                          Ubah File
                      </label>
                    </div>
                  @else
                    {{ Form::file('lembar_persetujuan', ['id' => 'lembar_persetujuan']) }}
                  @endif
                  
                  <br>

                  {{ Form::label('kartu_bimbingan', 'Kartu Bimbingan') }}
                  <span class="text-red">*</span>
                  @if(!empty($customRequest))
                      <div>
                        <input type="file" id="kartu_bimbingan" name="kartu_bimbingan" style="display: none;">
                        <a class="btn btn-primary" onclick="fnOpenPopUpWindow('preview file', '{{ route('student.request.preview', $kartu_bimbingan->id) }}')" id="ankleKartuBimbingan">
                        {{ $kartu_bimbingan->file_display_name }}
                        </a>
                        &nbsp;
                        <input type="checkbox" id="changeFile2" style="cursor: pointer;">
                        <label for="changeFile2" id="fileLabel2" style="vertical-align: middle; cursor: pointer;" data-upload="no">
                            Ubah File
                        </label>
                      </div>
                  @else
                      {{ Form::file('kartu_bimbingan', ['id' => 'kartu_bimbingan']) }}
                  @endif

                  <br>

                  {{ Form::label('lembar_turnitin', 'Lembar Turnitin') }}
                  <span class="text-red">*</span>
                  @if(!empty($customRequest))
                      <div>
                          <input type="file" id="lembar_turnitin" name="lembar_turnitin" style="display: none;">
                          <a class="btn btn-primary" onclick="fnOpenPopUpWindow('preview file', '{{ route('student.request.preview', $lembar_turnitin->id ) }}')" id="ankleTurnitin">
                              {{ $lembar_turnitin->file_display_name }}
                          </a>
                          &nbsp;
                          <input type="checkbox" id="changeFile3" style="cursor: pointer;">
                          <label for="changeFile3" id="fileLabel3" style="vertical-align: middle; cursor: pointer;" data-upload="no">
                              Ubah File
                          </label>
                      </div>
                  @else
                      {{ Form::file('lembar_turnitin', ['id' => 'lembar_turnitin']) }}
                  @endif

                  <br>

                  <div class="repeat-reason-wrapper">
                    {{ Form::label('repeat_reason', 'Alasan Daftar Ulang') }}
                    <span class="text-red">if you are re-requesting</span>
                    @if (!empty($customRequest))
                      {{ Form::text('repeat_reason', $customRequest->repeat_reason, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @else
                      {{ Form::text('repeat_reason', Input::old('repeat_reason'), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                    @endif
                  </div>
                  <!-- lembar pengesahan section -->
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                 {{ Form::button($btn_label, array('id' => 'btnSubmit', 'class' => 'btn btn-primary')) }}
                 <!-- for on backend file is editable or not -->
                 <input type="hidden" value="0" id="fileEditable" name="fileEditable">
                 <input type="hidden" value="0" id="fileEditable2" name="fileEditable2">
                 <input type="hidden" value="0" id="fileEditable3" name="fileEditable3">
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
        $(document).ready(function() {
            var repeatSessionWrapper = $('.repeat-reason-wrapper')[0];

            setRepeatReasonDisplay();
            
            function setRepeatReasonDisplay() {
              var sessionSelectorDefaultValue = $('.session-selector').val();

              if (sessionSelectorDefaultValue != '1') {
                repeatSessionWrapper.style.display = 'block';
              } else {
                repeatSessionWrapper.style.display = 'none';
              }
            };

            $('.session-selector').on( 'change', function () {   // for select box or datepicker who needs to trigger by change events
                  setRepeatReasonDisplay();
            });

            bindToogleChangeFile();
            bindMentorDropDownOnChange();

            $('#btnSubmit').click(function(e) {
                var confirmation = window.confirm("Apakah anda sudah yakin dengan pengisian formulir pendaftaran sidang kerja praktek ?");
                if(!confirmation) {
                    return false;
                }

                $('#frm').submit();
            });
        });

        function bindMentorDropDownOnChange() {
            $('#mentor_id').change(function() {
              var optionValue = $(this).children('option:selected').text().trim();
              $('#mentor_name').val(optionValue);
            });
        }

        function bindToogleChangeFile() {
            $('#changeFile').change(function() {
                var fileLabel = $('#fileLabel');
                var label = fileLabel.data('upload');
                var ankleTurnitin = $('#anklePersetujuan');
                var filePengesahan = $('#lembar_persetujuan');
                var fileEditable = $('#fileEditable');
                if(label === 'yes') {
                    fileEditable.val(0);
                    fileLabel.data('upload', 'no');
                    ankleTurnitin.show();
                    filePengesahan.hide();
                } else {
                    fileEditable.val(1);
                    fileLabel.data('upload', 'yes');
                    ankleTurnitin.hide();
                    filePengesahan.show();
                }
            });

            $('#changeFile2').change(function() {
                var fileLabel = $('#fileLabel2');
                var label = fileLabel.data('upload');
                var ankleKB = $('#ankleKartuBimbingan');
                var fileKB = $('#kartu_bimbingan');
                var fileEditable = $('#fileEditable2');
                if(label === 'yes') {
                  fileEditable.val(0);
                  fileLabel.data('upload', 'no');
                  ankleKB.show();
                  fileKB.hide();
                } else {
                  fileEditable.val(1);
                  fileLabel.data('upload', 'yes');
                  ankleKB.hide();
                  fileKB.show();
                }
            });

            $('#changeFile3').change(function() {
                var fileLabel = $('#fileLabel3');
                var label = fileLabel.data('upload');
                var ankleTurnitin = $('#ankleTurnitin');
                var fileTurnitin = $('#lembar_turnitin');
                var fileEditable = $('#fileEditable3');
                if(label === 'yes') {
                    fileEditable.val(0);
                    fileLabel.data('upload', 'no');
                    ankleTurnitin.show();
                    fileTurnitin.hide();
                } else {
                    fileEditable.val(1);
                    fileLabel.data('upload', 'yes');
                    ankleTurnitin.hide();
                    fileTurnitin.show();
                }
            });
        }

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