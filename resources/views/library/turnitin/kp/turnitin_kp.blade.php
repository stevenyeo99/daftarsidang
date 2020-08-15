@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main Content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="custom-add-new-button">
                        <a href="{{ route('turnitin_kp.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </a>
                    </div>
                    <br><br>
                    <div class="table-responsive">
                        <table id="turnitin_kp_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">NPM</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">File Name</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Uploaded By</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Tanggal Upload</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px"></th>
                                </tr>

                                <tr role="row" class="filter">
                                    <td>
                                        {{ Form::text('npm', null, ['id' => 'npm', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => 0]) }}
                                    </td>

                                    <td>
                                        {{ Form::text('file_display_name', null, ['id' => 'file_display_name', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => 1]) }}
                                    </td>

                                    <td>
                                        {{ Form::text('uploaded_by_user_name', null, ['id' => 'uploaded_by_user_name', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => 2]) }}
                                    </td>

                                    <td>
                                        {{ Form::text('uploaded_on', null, ['id' => 'uploaded_on', 'class' => 'width-100p form-control form-filter input-sm date-input', 'data-column' => 3, 'readonly' => true]) }}
                                    </td>

                                    <td></td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('custom_js')
    <script type="text/javascript">
        $(document).ready(function() 
        {
            var turnitin_kp_datatable = $('#turnitin_kp_datatable').DataTable({
                'autoWidth': false,
                orderCellsTop: true,
                responsive: false,
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                language: {
                    'url': "/assets/json/datatable-id-lang.json"
                },    
                ajax: {
                    url: '{{ route('turnitin_kp.list') }}',
                    data: function(data) {
                        data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    {data: 'npm', name:'npm'},
                    {data: {file_display_name: 'file_display_name', id: 'id'}, name: 'file_display_name', render: function(data, type, row, meta) {
                        if(type === 'display') {
                            var url = "{{ route('turnitin_kp.download', ':id') }}";
                            url = url.replace(':id', data.id);
                            data = '<a class="btn btn-primary" href="'+url+'">' + data.file_display_name + '</a>';
                        }
                        return data;
                    }},
                    {data:'uploaded_by_user_name', name:'uploaded_by_user_name'},
                    {data: 'uploaded_on', type: 'num', render: {
                        _: 'display',
                        sort: 'date'
                    }},
                    {data: 'actions', name: 'actions', "width": "120px", orderable: false, searchable: false}
                ]
            });

            $('.search-input-text').on('keyup click', function() {
                var i = $(this).attr('data-column'); // getting column index
                var v = $(this).val();
                turnitin_kp_datatable.columns(i).search(v).draw();
            });

            // initialize date picker
            $('.date-input').datepicker({
                format: 'd-M-yyyy',
            }).on('keyup', function(e) {
                if(e.keyCode == 8 || e.keyCode == 46) {
                    $(this).datepicker('setDate', null);
                }
            }).on('change', function() {
                var i = $(this).attr('data-column');
                var v= $(this).val();
                turnitin_kp_datatable.columns(i).search(v).draw();
            });    
        });
    </script>
@endpush