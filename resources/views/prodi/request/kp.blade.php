 @extends('layouts.master')

 @section('content')
 @include('shared.page_header')
 <!-- Main content -->
 <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    
                    <!-- data table -->
                    <div class="table-responsive">
                        <table id="kp_request_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1">NPM</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nama</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Angkatan</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Judul</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Status</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Status Sidang</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Email</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Aksi</th>
                                </tr>

                                <tr role="row">
                                    <td>
                                        {{ Form::text('npm', null, ['id' => 'npm', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('name', null, ['id' => 'name', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '1']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('generation', null, ['id' => 'generation', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '2']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('title', null, ['id' => 'title', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '3']) }}
                                    </td>

                                    <td>
                                        {{ Form::select('status', $statuses, $defaultStatusSelection, ['id' => 'status', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'All', 'data-column' => '4']) }}
                                    </td>

                                    <td>
                                        {{ Form::select('session_status_status', $session_statuses, null, ['id' => 'session_status_status', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'All', 'data-column' => '5'])}}
                                    </td>

                                    <td>
                                        {{ Form::text('email', null, ['id' => 'email', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '6']) }}
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
        $(document).ready(function() {
            // initialize datatables
            var dtKPRequest = $('#kp_request_datatable').DataTable({
                'autoWidth': false,
                orderCellsTop: true,
                responsive: false,
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                language: {
                    'url': '/assets/json/datatable-id-lang.json'
                },
                ajax: {
                    url: '{{route('prodi.kp_record_list') }}',
                    data: function(data) {
                        data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'npm', name: 'npm', "width": "80px" },
                    { data: 'name', name: 'name', "width": "120px" },
                    { data: 'generation', name: 'generation', "width": "80px" },
                    { data: 'title', name: 'title', "width": "120px" },
                    { data: 'status', name: 'status', "width": "80px" },
                    { data: 'session_status_status', name: 'session_status_status', "width": "80px" },
                    { data: 'email', name: 'email', "width": "160px"},
                    { data: 'actions', name: 'actions', "width": "160px", orderable: false, searchable: false },
                ]
            });

             $('.search-input-text').on('keyup click', function() {
                var i = $(this).attr('data-column');
                var v = $(this).val();
                dtKPRequest.columns(i).search(v).draw();
            });

            $('.search-input-select').on( 'change', function () {   // for select box or datepicker who needs to trigger by change events
                var i =$(this).attr('data-column');
                var v =$(this).val();
                dtKPRequest.columns(i).search(v).draw();
            });

            $('.search-input-select').change();
        });
    </script>
 @endpush