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
                  @if ($customRequest->type == \App\Enums\CreationType::Skripsi || $customRequest->type == \App\Enums\CreationType::Tesis)
                    <a class="btn btn-box-tool blue-col" href="{{ route('request.skripsi') }}">
                  @else
                    <a class="btn btn-box-tool blue-col" href="{{ route('request.kp') }}">
                  @endif
                    <i class="fa fa-arrow-left"></i>
                    Kembali
                  </a>
                {{-- </button> --}}
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if ($customRequest->type == \App\Enums\CreationType::Skripsi || $customRequest->type == \App\Enums\CreationType::Tesis)
              {{ Form::open(array('url' => route('request.skripsi.change.session.status', $customRequest->id))) }}
            @else
              {{ Form::open(array('url' => route('request.kp.change.session.status', $customRequest->id))) }}
            @endif
              <div class="box-body">
                <div class="form-group">
                  {{ Form::label('status', 'Status Sidang') }}
                  <span class="text-red">*</span>
                  @if (!empty($session_status))
                    {{ Form::select('status', $statuses, $session_status->status, array('class' => 'width-100p form-control select2 session-status-selector', 'placeholder' => '')) }}
                  @else
                    {{ Form::select('status', $statuses, null, array('class' => 'width-100p form-control select2 session-status-selector', 'placeholder' => '')) }}
                  @endif
                  <div class="session-date-wrapper">
                    <br>
                    {{ Form::label('date', 'Tanggal Sidang') }}
                    <span class="text-red">*</span>
                    @if (!empty($session_status))
                      @if ($session_status->date != null)
                        {{ Form::text('date', date('d M Y', strtotime($session_status->date)), array('id' => 'datepicker', 'class' => 'datepicker-me-class form-control pull-right', 'placeholder' => 'Kosong...')) }}
                      @else
                        {{ Form::text('date', date('d M Y'), array('id' => 'datepicker', 'class' => 'datepicker-me-class form-control pull-right', 'placeholder' => 'Kosong...')) }}
                      @endif
                    @else
                      {{ Form::text('date', date('d M Y'), array('id' => 'datepicker', 'class' => 'datepicker-me-class form-control pull-right', 'placeholder' => 'Kosong...')) }}
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
</section>
@endsection

@push('custom_js')
    <script type="text/javascript">
        $(document).ready(function() {
            var sessionDateWrapper = $('.session-date-wrapper')[0];

            setSessionDateDisplay();
            
            function setSessionDateDisplay() {
              var sessionSelectorDefaultValue = $('.session-status-selector').val();

              if (sessionSelectorDefaultValue == '0') {
                sessionDateWrapper.style.display = 'block';
              } else {
                sessionDateWrapper.style.display = 'none';
              }
            };

            $('.session-status-selector').on( 'change', function () {   // for select box or datepicker who needs to trigger by change events
                  setSessionDateDisplay();
            });
        });

    </script>
@endpush