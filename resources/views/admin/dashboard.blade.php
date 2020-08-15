@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <table id="record_datatable" class="data-table table table-bordered table-hover">
                        <thead>
                            <tr role="row">
                                <th class="text-center vcenter" rowspan="1" colspan="1">NPM</th>
                                <th class="text-center vcenter" rowspan="1" colspan="1">Nama</th>
                                <th class="text-center vcenter" rowspan="1" colspan="1">Program Studi</th>
                                <th class="text-center vcenter" rowspan="1" colspan="1">Status</th>
                                <th class="text-center vcenter" rowspan="1" colspan="1">Email</th>
                                <th class="text-center vcenter" rowspan="1" colspan="1">Aksi</th>
                            </tr>
                            <tr role="row" class="filter">
                                <td>
                                    {{ Form::text('npm', null, ['id' => 'npm', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0']) }}
                                </td>
                                <td>
                                    {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control form-filter input-sm search-input-text', 'data-column' => '1']) }}
                                </td>
                                <td>
                                    {{ Form::text('program_study', null, ['id' => 'program_study', 'class' => 'form-control form-filter input-sm search-input-text', 'data-column' => '2']) }}
                                </td>
                                <td>
                                    {{ Form::select('status', [] , null , ['id' => 'status', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'All', 'data-column' => '3']) }}
                                </td>
                                <td>
                                    {{ Form::text('email', null, ['id' => 'email', 'class' => 'form-control form-filter input-sm search-input-text', 'data-column' => '4']) }}
                                </td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
            // var recordDatatable = $('#record_datatable').DataTable(
            // {
            //     'autoWidth' : false,
            //     orderCellsTop: true,
            //     responsive: true,
            //     processing: true,
            //     serverSide: true,
            //     iDisplayLength: 10,
            //     language: {
            //         'url': "/assets/json/datatable-id-lang.json"
            //     },
            //     ajax: {
            {{-- //         url:  '{{ route('dashboard_record.list') }}', --}}
            //         data: function(data) {
            {{-- //             data._token = '{{ csrf_token() }}'; --}}
            //         },
            //         type: 'POST',
            //     },
            //     columns: [
            //         { data: 'client_name', name: 'client_name', "width": "10%" },
            //         { data: 'date_in', name: 'date_in', "width": "10%" },
            //         { data: 'category_name', name: 'category_name', "width": "10%" },
            //         { data: 'length', name: 'rd.length', "width": "5%" },
            //         { data: 'width', name: 'rd.width', "width": "5%" },
            //         { data: 'height', name: 'rd.height', "width": "5%" },
            //         { data: 'description', name: 'rd.description', "width": "15%" },
            //         { data: 'price', name: 'price', "width": "10%" },
            //         { data: 'status', name: 'status', "width": "10%" },
            //         { data: 'actions', name: 'actions', "width": "20%" , orderable: false, searchable: false },
            //     ],
            //     searchCols: [
            //         null ,
            //         { "search" : 0} ,
            //     ],
            // });

            // $('.search-input-text').on( 'keyup click', function () {   // for text boxes
            //     var i =$(this).attr('data-column');  // getting column index
            //     var v =$(this).val();  // getting search input value
            //     recordDatatable.columns(i).search(v).draw();
            // } );
            // $('.search-input-select').on( 'change', function () {   // for select box or datepicker who needs to trigger by change events
            //     var i =$(this).attr('data-column');
            //     var v =$(this).val();
            //     recordDatatable.columns(i).search(v).draw();
            // } );
        });

    </script>
@endpush
