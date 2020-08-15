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
                  <a class="btn btn-box-tool blue-col" href="{{ route('student.request.skripsi') }}">
                    <i class="fa fa-arrow-left"></i>
                    Kembali
                  </a>
                {{-- </button> --}}
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if (!empty($customRequest))
              {{ Form::open(array('url' => route('student.request.skripsi.update', $customRequest->id), 'id' => 'frm', 'enctype' => 'multipart/form-data')) }}
            @else
              {{ Form::open(array('url' => route('student.request.skripsi.create'), 'id' => 'frm', 'enctype' => 'multipart/form-data')) }}
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
                  {{ Form::label('type', 'Tipe') }}
                  <span class="text-red">*</span>
                  @if (!empty($customRequest))
                    {{ Form::select('type', $types, $customRequest->type, array('class' => 'width-100p form-control select2', 'placeholder' => '', 'disabled')) }}
                  @else
                    {{ Form::select('type', $types, null, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                  @endif
                  <br><br>
                  {{ Form::label('title', 'Judul') }}
                  <span class="text-red">*</span>
                  @if (!empty($customRequest))
                    {{ Form::text('title', $customRequest->title, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('title', Input::old('title'), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @endif
                  <br>
                  <div class="form-group">
                      <div class="col-xs-6 p-l-0">
                            {{ Form::label('start_date', 'Tanggal Mulai Bimbingan') }}
                            <span class="text-red">*sesuai yg tertera di kartu bimbingan skripsi</span>
                            @if (!empty($customRequest))
                                {{ Form::text('start_date', date('d M Y', strtotime($customRequest->start_date)), array('id' => 'datepicker', 'class' => 'datepicker-me-class form-control pull-right', 'placeholder' => 'Kosong...', 'data-date-end-date' => '0d')) }}
                            @else
                                {{ Form::text('start_date', Input::old('start_date'), array('id' => 'datepicker', 'class' => 'datepicker-me-class form-control pull-right', 'placeholder' => 'Kosong...', 'data-date-end-date' => '0d')) }}
                            @endif
                      </div>
                      <div class="col-xs-6 p-l-0 p-r-0">
                          {{ Form::label('end_date', 'Tanggal Akhir Bimbingan') }}
                          <span class="text-red">*sesuai yg tertera di kartu bimbingan skripsi</span>
                            @if (!empty($customRequest))
                                {{ Form::text('end_date', date('d M Y', strtotime($customRequest->end_date)), array('id' => 'datepicker', 'class' => 'datepicker-me-class form-control pull-right', 'placeholder' => 'Kosong...', 'data-date-end-date' => '0d')) }}
                            @else
                                {{ Form::text('end_date', Input::old('end_date'), array('id' => 'datepicker', 'class' => 'datepicker-me-class form-control pull-right', 'placeholder' => 'Kosong...', 'data-date-end-date' => '0d')) }}
                            @endif
                      </div>
                  </div>
                  <br/><br/><br/>
                  {{ Form::label('mentor_name', 'Name Pembimbing') }}
                  <span class="text-red">*</span>
                  @if (!empty($customRequest))
                    {{ Form::select('mentor_id', $prodi_user, $customRequest->mentor_id, array('id' => 'mentor_id', 'class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                    {{ Form::text('mentor_name', $customRequest->mentor_name, array('id' => 'mentor_name', 'readonly' => true, 'hidden' => true)) }}
                  @else
                    {{ Form::select('mentor_id', $prodi_user, null, array('id' => 'mentor_id', 'class' => 'width-100p form-control select2', 'placeholder' => ''))}}
                    {{ Form::text('mentor_name', Input::old('mentor_name'), array('id' => 'mentor_name', 'readonly' => true, 'hidden' => true)) }}
                  @endif
                  
                  <br><br>

                  {{ Form::label('sa_point', 'SA Point') }}
                  @if(!empty($customRequest))
                    {{ Form::text('sa_point', $customRequest->sa_point, array('id' => 'sa_point', 'class' => 'form-control', 'readonly' => true)) }}
                  @else
                    {{ Form::text('sa_point', $saPoint, array('id' => 'sa_point', 'class' => 'form-control', 'readonly' => true)) }}
                  @endif

                  <br>

                  {{ Form::label('lembar_persetujuan', 'Lembar Persetujuan') }}
                    <span class="text-red">*</span>
                    @if(!empty($customRequest))
                    <div>
                      <input type="file" id="lembar_persetujuan" name="lembar_persetujuan" style="display: none;">
                      <a class="btn btn-primary" onclick="fnOpenPopUpWindow('preview file', '{{ route('student.request.preview_ilmiah',  $lembar_persetujuan->id) }}')" id="anklePersetujuan">
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
                        <a class="btn btn-primary" onclick="fnOpenPopUpWindow('preview file', '{{ route('student.request.preview_ilmiah', $kartu_bimbingan->id) }}')" id="ankleKartuBimbingan">
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
                          <a class="btn btn-primary" onclick="fnOpenPopUpWindow('preview file', '{{ route('student.request.preview_ilmiah', $lembar_turnitin->id ) }}')" id="ankleTurnitin">
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

                  {{ Form::label('lembar_plagiat', 'Lembar Plagiat') }}
                  <span class="text-red">*</span>
                  @if(!empty($customRequest))
                      <div>
                          <input type="file" id="lembar_plagiat" name="lembar_plagiat" style="display: none;">
                          <a class="btn btn-primary" onclick="fnOpenPopUpWindow('preview file', '{{ route('student.request.preview_ilmiah', $lembar_plagiat->id ) }}')" id="anklePlagiat">
                              {{ $lembar_plagiat->file_display_name }}
                          </a>
                          &nbsp;
                          <input type="checkbox" id="changeFile4" style="cursor: pointer;">
                          <label for="changeFile4" id="fileLabel4" style="vertical-align: middle; cursor: pointer;" data-upload="no">
                              Ubah File
                          </label>
                      </div>
                  @else
                      {{ Form::file('lembar_plagiat', ['id' => 'lembar_plagiat']) }}
                  @endif

                  <br>

                  {{ Form::label('foto_meteor', 'Foto Meteor') }}
                  <span class="text-red">*</span>
                  @if(!empty($customRequest))
                      <div>
                          <input type="file" id="foto_meteor" name="foto_meteor" style="display: none;" accept="image/*">
                          <a class="btn btn-primary" onclick="fnOpenPopUpWindow('preview file', '{{ route('student.request.preview_ilmiah', $foto_meteor->id ) }}')" id="ankleFotoMeteor">
                              {{ $foto_meteor->file_display_name }}
                          </a>
                          &nbsp;
                          <input type="checkbox" id="changeFile5" style="cursor: pointer;">
                          <label for="changeFile5" id="fileLabel5" style="vertical-align: middle; cursor: pointer;" data-upload="no">
                              Ubah File
                          </label>
                      </div>
                  @else
                      {{ Form::file('foto_meteor', ['id' => 'foto_meteor', 'accept' => 'image/*']) }}
                  @endif

                  <br>

                  {{ Form::label('official_toeic', 'Official TOEIC') }}
                  <span class="text-red">*</span>
                  @if(!empty($customRequest))
                      <div>
                          <input type="file" id="official_toeic" name="official_toeic" style="display: none;">
                          <a class="btn btn-primary" onclick="fnOpenPopUpWindow('preview file', '{{ route('student.request.preview_ilmiah', $official_toeic->id ) }}')" id="ankleOfficialToeic">
                              {{ $official_toeic->file_display_name }}
                          </a>
                          &nbsp;
                          <input type="checkbox" id="changeFile6" style="cursor: pointer;">
                          <label for="changeFile6" id="fileLabel6" style="vertical-align: middle; cursor: pointer;" data-upload="no">
                              Ubah File
                          </label>
                      </div>
                  @else
                      {{ Form::file('official_toeic', ['id' => 'official_toeic']) }}
                  @endif

                  <br>

                  {{ Form::label('abstract_uclc', 'Abstract UCLC') }}
                  <span class="text-red">*</span>
                  @if(!empty($customRequest))
                      <div>
                          <input type="file" id="abstract_uclc" name="abstract_uclc" style="display: none;">
                          <a class="btn btn-primary" onclick="fnOpenPopUpWindow('preview file', '{{ route('student.request.preview_ilmiah', $abstract_uclc->id ) }}')" id="ankleAbstractUclc">
                              {{ $abstract_uclc->file_display_name }}
                          </a>
                          &nbsp;
                          <input type="checkbox" id="changeFile7" style="cursor: pointer;">
                          <label for="changeFile7" id="fileLabel7" style="vertical-align: middle; cursor: pointer;" data-upload="no">
                              Ubah File
                          </label>
                      </div>
                  @else
                      {{ Form::file('abstract_uclc', ['id' => 'abstract_uclc']) }}
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

                    <br>

                  </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                 {{ Form::button($btn_label, array('id' => 'btnSubmit', 'class' => 'btn btn-primary')) }}
                 <!-- for on backend file is editable or not -->
                 <input type="hidden" value="0" id="fileEditable" name="fileEditable">
                 <input type="hidden" value="0" id="fileEditable2" name="fileEditable2">
                 <input type="hidden" value="0" id="fileEditable3" name="fileEditable3">
                 <input type="hidden" value="0" id="fileEditable4" name="fileEditable4">
                 <input type="hidden" value="0" id="fileEditable5" name="fileEditable5">
                 <input type="hidden" value="0" id="fileEditable6" name="fileEditable6">
                 <input type="hidden" value="0" id="fileEditable7" name="fileEditable7">
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
                var confirmation = window.confirm("Apakah anda sudah yakin dengan pengisian formulir pendaftaran sidang Skripsi/Tesis ?");
                if(!confirmation) {
                    return false;
                }

                $('#frm').submit();
            });
        });

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

            $('#changeFile4').change(function() {
                var fileLabel = $('#fileLabel4');
                var label = fileLabel.data('upload');
                var ankleKB = $('#anklePlagiat');
                var fileKB = $('#lembar_plagiat');
                var fileEditable = $('#fileEditable4');
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

            $('#changeFile5').change(function() {
                var fileLabel = $('#fileLabel5');
                var label = fileLabel.data('upload');
                var ankleTurnitin = $('#ankleFotoMeteor');
                var fileTurnitin = $('#foto_meteor');
                var fileEditable = $('#fileEditable5');
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

            $('#changeFile6').change(function() {
                var fileLabel = $('#fileLabel6');
                var label = fileLabel.data('upload');
                var ankleTurnitin = $('#ankleOfficialToeic');
                var fileTurnitin = $('#official_toeic');
                var fileEditable = $('#fileEditable6');
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

            $('#changeFile7').change(function() {
                var fileLabel = $('#fileLabel7');
                var label = fileLabel.data('upload');
                var ankleTurnitin = $('#ankleAbstractUclc');
                var fileTurnitin = $('#abstract_uclc');
                var fileEditable = $('#fileEditable7');
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

        function bindMentorDropDownOnChange() {
            $('#mentor_id').change(function() {
              var optionValue = $(this).children('option:selected').text().trim();
              $('#mentor_name').val(optionValue);
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