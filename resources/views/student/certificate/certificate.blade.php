@extends('layouts.master')

@section('content')
@include('shared.page_header')

<div class="m-b-15 m-l-15 m-r-15 alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info-circle"></i> Pemberitahuan!</h4>

    <strong>
        Data sertifikasi ini akan digunakan untuk pencetakan SKPI (Surat Keterangan Pengganti Ijazah).
    </strong>
</div>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="custom-add-new-button">
                        @if ($should_show_create) 
                            <a
                            href="{{ route('student.certificate.create') }}"
                            class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                Tambah
                            </a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table id="certificate_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nama</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Tempat</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Tahun</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Aksi</th>
                                </tr>
                                <tr role="row" class="filter">
                                    <td>
                                        {{ Form::text('name', null, ['id' => 'name', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('place', null, ['id' => 'place', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '1']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('year', null, ['id' => 'year', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '2']) }}
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
                    url:  '{{ route('student.certificate.list') }}',
                    data: function(data) {
                         data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'name', name: 'name', "width": "120px" },
                    { data: 'place', name: 'place', "width": "160px" },
                    { data: 'year', name: 'year', "width": "80px" },
                    { data: 'actions', name: 'actions', "width": "160px" , orderable: false, searchable: false },
                ],
            });

            $('.search-input-text').on( 'keyup click', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
                var v =$(this).val();  // getting search input value
                certificateDatatable.columns(i).search(v).draw();
            } );
            $('.search-input-select').on( 'change', function () {   // for select box or datepicker who needs to trigger by change events
                var i =$(this).attr('data-column');
                var v =$(this).val();
                certificateDatatable.columns(i).search(v).draw();
            } );
        });

    </script>
@endpush
