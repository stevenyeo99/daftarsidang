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
                {{-- <button type="button"> --}}
                  <a class="btn btn-box-tool blue-col" href="{{ route('semesters') }}">
                    <i class="fa fa-arrow-left"></i>
                    Kembali
                  </a>
                {{-- </button> --}}
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if (!empty($semester))
              {{ Form::open(array('url' => route('semesters.update', $semester->id))) }}
            @else
              {{ Form::open(array('url' => route('semesters.create'))) }}
            @endif
              <div class="box-body">
                <div class="form-group">
                  {{ Form::label('year', 'Tahun') }}
                  <span class="text-red">*</span>
                  @if (!empty($semester))
                    {{ Form::text('year', substr($semester->year, 0, 4), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @else
                    {{ Form::text('year', Input::old('year'), array('class' => 'form-control', 'placeholder' => 'Kosong...')) }}
                  @endif
                  <br>
                  {{ Form::label('type', 'Tipe') }}
                  <span class="text-red">*</span>
                  @if (!empty($semester))
                      {{ Form::select('type', $types, $semester->type, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                  @else
                      {{ Form::select('type', $types, null, array('class' => 'width-100p form-control select2', 'placeholder' => '')) }}
                  @endif
                  <br>

                  <div class="checkbox icheck">
                    <label class="">
                      @if (!empty($semester))
                        <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                            <input
                            class="icheck-checkbox"
                            type="checkbox"
                            name="is_active"
                            {{ $semester->is_active ? 'checked' : '' }}
                            style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">
                            <ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                        </div> Aktif
                      @else
                        <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                            <input
                            class="icheck-checkbox"
                            type="checkbox"
                            name="is_active"
                            style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">
                            <ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                        </div> Aktif
                      @endif
                    </label>
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
</section>
@endsection

@push('custom_js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('input.icheck-checkbox').iCheck({
              checkboxClass: 'icheckbox_square-blue',
              radioClass: 'iradio_square-blue',
              increaseArea: '20%' /* optional */
            });
        });

    </script>
@endpush