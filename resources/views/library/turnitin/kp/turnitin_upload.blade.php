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
                            <a class="btn btn-box-tool blue-col" href="{{ route('turnitin_kp') }}">
                                <i class="fa fa-arrow-left"></i>
                                Kembali
                            </a>
                        {{-- </button> --}}
                    </div>
                </div>
                
                <!-- form start -->
                @if(!empty($turnitinFile))
                    {{ Form::open(array('url' => route('turnitin_kp.update', $turnitinFile->id), 'files' => true)) }}
                @else
                    {{ Form::open(array('url' => route('turnitin_kp.create'), 'files' => true)) }}
                @endif
                <div class="box-body">
                    <div class="form-group">
                        {{ Form::hidden('type', 'KP') }}
                        {{ Form::label('npm', 'NPM') }}
                        <span class="text-red">*</span>
                        @if(!empty($turnitinFile))
                            {{ Form::text('npm', $turnitinFile->npm, array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @else
                            {{ Form::text('npm', Input::old('npm'), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                        @endif
                    </div>

                    <div class="form-group">
                        {{ Form::label('file_turnitin') }}
                        <span class="text-red">*</span>
                        @if(!empty($turnitinFile))
                            <div>
                                <input type="file" id="fileTurnitin" name="file" style="display: none;">
                                <a class="btn btn-primary" onclick="fnOpenPopUpWindow('preview mime', '{{ route('turnitin_kp.preview', $turnitinFile->id) }}')" id="ankleTurnitin">{{ $turnitinFile->file_display_name }}</a>
                                &nbsp;
                                <input type="checkbox" id="changeFile" style="cursor: pointer;">
                                <label for="changeFile" id="fileLabel" style="vertical-align: middle; cursor: pointer;" data-upload="no">
                                    Ubah File
                                </label>
                            </div>
                        @else
                            {{ Form::file('file') }}
                        @endif
                    </div>
                </div>

                <div class="box-footer">
                    {{ Form::submit($btn_label, array('class' => 'btn btn-primary')) }}
                    @if(!empty($turnitinFile))
                        <a class="btn btn-warning" href="{{ route('turnitin_kp.download', $turnitinFile->id) }}" title="FILE TURNITIN">
                            <i class="fa fa-download"></i> Unduh
                        </a>

                        <!-- for on backend file is editable or not -->
                        <input type="hidden" value="0" id="fileEditable" name="fileEditable">
                    @endif
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</section>
@endsection

@push('custom_js')
    <script>
        $(document).ready(function() {
            bindToogleChangeFile();
        });

        function bindToogleChangeFile() {
            $('#changeFile').change(function() {
                var fileLabel = $('#fileLabel');
                var label = fileLabel.data('upload');
                var ankleTurnitin = $('#ankleTurnitin');
                var fileTurnitin = $('#fileTurnitin');
                var fileEditable = $('#fileEditable');
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