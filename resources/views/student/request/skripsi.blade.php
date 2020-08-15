@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title text-info">Form Yang Perlu di Download</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body text-red">
                    <span class="p-b-10 dis-block">
                        *Silahkan unduh dan lengkapi formulir yang tertera dibawah ini untuk diserahkan ke BAAK beserta seluruh persyaratan sidang*<br>
                    </span>

                    Sebelum diserahkan ke BAAK sudah harus ada :
                    <ol>
                        <li>Cap dari UCLC</li>
                        <li>Cap telah foto dari Meteor Photo Studio</li>
                        <li>Cap dari Keuangan (Bebas Keuangan)</li>
                        <li>Tanda Tangan Kepala Program Studi</li>
                    </ol>
                    Untuk mengunduh formulirnya , silahkan klik
                    <a download="Form Kelengkapan Berkas Pendaftaran Sidang dan Wisuda" href="../../../../assets/img/form-kelengkapan-berkas-pendaftaran-sidang-dan-wisuda.jpg" title="Form Kelengkapan Berkas Pendaftaran Sidang dan Wisuda">
                        Form Kelengkapan Berkas Pendaftaran Sidang dan Wisuda.jpg
                    </a>
                    <br>
                    Silahkan isi formulir ini juga :
                    <a download="Form SKPI" href="../../../../assets/img/FORM SKPI.jpg" title="Form SKPI">
                        Form SKPI.jpg
                    </a>
                    <br>
                    *Jika ada perbedaan data nama & tempat tanggal lahir, silahkan isi formulir ini juga :
                    <a download="Surat Pernyataan Beda Identitas" href="../../../../assets/img/surat-pernyataan-beda-identitas.jpg" title="Surat Pernyataan Beda Identitas">
                        Surat Pernyataan Beda Identitas.jpg
                    </a>
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-body">
                    <div class="custom-add-new-button">
                        @if (Gate::allows('is-student') && Gate::allows('is-student-semester-active')) 
                            <a
                            href="{{ route('student.request.skripsi.create') }}"
                            class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                {{-- Make a new request --}}
                                Daftar
                            </a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table id="skripsi_request_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Sesi</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Tipe</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Judul</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Status</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Tanggal Mulai Bimbingan</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Tanggal Akhir Bimbingan</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nama Pembimbing</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Aksi</th>
                                </tr>
                                <tr role="row" class="filter">
                                    <td>
                                        {{ Form::text('session', null, ['id' => 'session', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0']) }}
                                    </td>
                                    <td>
                                        {{ Form::select('type', $types, null , ['id' => 'type', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'All', 'data-column' => '1']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('title', null, ['id' => 'title', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '2']) }}
                                    </td>
                                    <td>
                                        {{ Form::select('status', $statuses, null , ['id' => 'status', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'All', 'data-column' => '3']) }}
                                    </td>
                                    <td>
                                        <div class="margin-bottom-5" style="position:relative">
                                          <div class="input-group">
                                            {{ Form::text('start_date', null, ['id' => 'datepicker', 'class' => 'datepicker-me-class form-control form-filter input-sm search-input-select', 'placeholder' => 'Tanggal Mulai Bimbingan', 'data-column' => '4']) }}
                                          </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="margin-bottom-5" style="position:relative">
                                          <div class="input-group">
                                            {{ Form::text('end_date', null, ['id' => 'datepicker', 'class' => 'datepicker-me-class form-control form-filter input-sm search-input-select', 'placeholder' => 'Tanggal Akhir Bimbingan', 'data-column' => '5']) }}
                                          </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ Form::text('mentor_name', null, ['id' => 'mentor_name', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '6']) }}
                                    </td>
                                    <td></td>
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
@endsection

@push('custom_js')
    <script type="text/javascript">
        $(document).ready(function() {
            var skripsiRequestDatatable = $('#skripsi_request_datatable').DataTable(
            {
                'autoWidth' : false,
                orderCellsTop: true,
                responsive: false,
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                language: {
                    'url': "/assets/json/datatable-id-lang.json"
                },
                ajax: {
                    url:  '{{ route('student.skripsi_record.list') }}',
                    data: function(data) {
                         data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'session', name: 'session', "width": "120px" },
                    { data: 'type', name: 'type', "width": "80px" },
                    { data: 'title', name: 'title', "width": "120px" },
                    { data: 'status', name: 'status', "width": "80px" },
                    { data: 'start_date', name: 'start_date', "width": "80px" },
                    { data: 'end_date', name: 'end_date', "width": "80px" },
                    { data: 'mentor_name', name: 'mentor_name', "width": "120px" },
                    { data: 'actions', name: 'actions', "width": "160px" , orderable: false, searchable: false },
                ],
            });

            $('.search-input-text').on( 'keyup click', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
                var v =$(this).val();  // getting search input value
                skripsiRequestDatatable.columns(i).search(v).draw();
            } );
            $('.search-input-select').on( 'change', function () {   // for select box or datepicker who needs to trigger by change events
                var i =$(this).attr('data-column');
                var v =$(this).val();
                skripsiRequestDatatable.columns(i).search(v).draw();
            } );
        });

    </script>
@endpush
