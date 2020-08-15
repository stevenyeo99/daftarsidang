@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="custom-add-new-button">
                        @if (Gate::allows('is-student') && Gate::allows('is-student-semester-active')) 
                            <a
                            href="{{ route('student.request.create') }}"
                            class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                {{-- Make a new request --}}
                                Daftar
                            </a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table id="kp_request_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Sesi</th>
                                    {{-- <th class="text-center vcenter" rowspan="1" colspan="1">Type</th> --}}
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Judul</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Status</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nama Pembimbing</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Aksi</th>
                                </tr>
                                <tr role="row" class="filter">
                                    <td>
                                        {{ Form::text('session', null, ['id' => 'session', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('title', null, ['id' => 'title', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '1']) }}
                                    </td>
                                    <td>
                                        {{ Form::select('status', $statuses, null , ['id' => 'status', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'All', 'data-column' => '2']) }}
                                    </td>
                                    <td>
                                        {{ Form::text('mentor_name', null, ['id' => 'mentor_name', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '3']) }}
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
            var kpRequestDatatable = $('#kp_request_datatable').DataTable(
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
                    url:  '{{ route('student.kp_record.list') }}',
                    data: function(data) {
                         data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'session', name: 'session', "width": "120px" },
                    // { data: 'type', name: 'type', "width": "80px" },
                    { data: 'title', name: 'title', "width": "120px" },
                    { data: 'status', name: 'status', "width": "80px" },
                    { data: 'mentor_name', name: 'mentor_name', "width": "120px" },
                    { data: 'actions', name: 'actions', "width": "160px" , orderable: false, searchable: false },
                ],
            });

            $('.search-input-text').on( 'keyup click', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
                var v =$(this).val();  // getting search input value
                kpRequestDatatable.columns(i).search(v).draw();
            } );
            
            $('.search-input-select').on( 'change', function () {   // for select box or datepicker who needs to trigger by change events
                var i =$(this).attr('data-column');
                var v =$(this).val();
                kpRequestDatatable.columns(i).search(v).draw();
            });
        });

    </script>
@endpush
