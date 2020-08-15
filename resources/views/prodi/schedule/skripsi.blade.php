@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="dtPenjadwalanSkripsi" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1">NPM</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nama</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Dosen Pembimbing</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Dosen Penguji</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Tanggal-Waktu Sidang</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Status Penjadwalan</th>
                                    <!-- <th class="text-center vcenter" rowspan="1" colspan="1">Ruangan Sidang</th> -->
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
                                        {{ Form::text('dosen_pembimbing_name', null, ['id' => 'dosen_pembimbing_name', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '2']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('dosen_penguji_name', null, ['id' => 'dosen_penguji_name', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '3']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('tanggal_sidang', null, ['id' => 'tanggal_sidang', 'class' => 'width-100p form-control form-filter input-sm search-input-datetime', 'data-column' => '4']) }}
                                    </td>

                                    <td>
                                        {{ Form::select('status_penjadwalan', ['ONGOING' => 'Proses Penjadwalan', 'COMPLETED' => 'Penjadwalan Selesai', 'EXPIRED' => 'Penjadwalan Lama'], 'ONGOING', array('class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'ALL', 'data-column' => '5')) }}
                                    </td>

                                    <td>
                                        
                                    </td>
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
            var dtPenjadwalanSkripsi = $('#dtPenjadwalanSkripsi').DataTable({
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
                    url: '{{ route('prodi.penjadwalan_skripsi_list') }}',
                    data: function(data) {
                        data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    { data: 'npm', name: 'npm', "width": "80px" },
                    { data: 'name', name: 'name', "width": "120px" },
                    { data: 'dosen_pembimbing_name', name: 'dosen_pembimbing_name', "width": "120px" },
                    { data: 'dosen_penguji_name', name: 'dosen_penguji_name', "width": "120px" },
                    { data: 'tanggal_sidang', name: 'tanggal_sidang', type: 'num', render: {
                        _: 'display',
                        sort: 'datetime'
                    }, "width": "130px" },
                    { data: 'status_penjadwalan', name: 'status_penjadwalan', "width": "120px" },
                    { data: 'actions', name: 'actions', "width": "160px", orderable: false, searchable: false },
                ]
            });

            $('#tanggal_sidang').datepicker({
                format: 'd-M-yyyy',
            }).attr('readonly', true)
            .on('keyup', function(e) {
                if(e.keyCode == 8 || e.keyCode == 46) {
                    $(this).val('');
                    $(this).change();
                }
            })
            .on('change', function() {
                var i = $(this).attr('data-column');
                var v = $(this).val();
                dtPenjadwalanSkripsi.columns(i).search(v).draw();
            });

            $('.search-input-text').on('keyup click', function() {
                var i = $(this).attr('data-column');
                var v = $(this).val();
                dtPenjadwalanSkripsi.columns(i).search(v).draw();
            })

            $('.search-input-select').on('change', function() {
                var i = $(this).attr('data-column');
                var v = $(this).val();
                dtPenjadwalanSkripsi.columns(i).search(v).draw();
            });
            
            $('.search-input-select').change();
        });
    </script>
@endpush