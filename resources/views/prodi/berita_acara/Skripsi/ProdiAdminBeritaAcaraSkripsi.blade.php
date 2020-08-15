@extends('layouts.master')

@section('content')
@include('shared.page_header')
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="dtBeritaAcaraSkripsi" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1">NPM</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nama</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Judul</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Ketua Penguji</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Penguji</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Tanggal Sidang</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Nilai</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1">Status</th>
                                </tr>

                                <tr role="row">
                                    <td>
                                        {{ Form::text('npm', null, array('id' => 'npm', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => 0)) }}
                                    </td>

                                    <td>
                                        {{ Form::text('name', null, array('id' => 'name', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => 1)) }}
                                    </td>

                                    <td>
                                        {{ Form::text('title', null, array('id' => 'title', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => 2)) }}
                                    </td>

                                    <td>
                                        {{ Form::text('ketua_penguji', null, array('id' => 'ketua_penguji', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => 3)) }}
                                    </td>
                                    
                                    <td>
                                        {{ Form::text('penguji', null, array('id' => 'penguji', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => 4)) }}
                                    </td>

                                    <td>
                                        {{ Form::text('tanggal_sidang', null, array('id' => 'tanggal_sidang', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => 5, 'readonly' => true))}}
                                    </td>

                                    <td></td>

                                    <td>
                                        {{ Form::select('status', $statusBeritaAcaraArr, 2, array('id' => 'status', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'all', 'data-column' => 7)) }}
                                    </td>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
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
        var dtBeritaAcaraSkripsi = $('#dtBeritaAcaraSkripsi').DataTable({
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
                url: '{{ route('prodi_admin.berita_acara_skripsi_list') }}',
                data: function(data) {
                    data._token = '{{ csrf_token() }}';
                },
                type: 'POST',
            },
            columns: [
                { data: 'npm', name: 'npm', "width": "80px" },
                { data: 'name', name: 'name', "width": "120px" },
                { data: 'title', name: 'title', "width": "150px" },
                { data: 'ketua_penguji', name: 'ketua_penguji', "width": "120px" },
                { data: 'penguji', name: 'penguji', "width": "120px" },
                { data: 'tanggal_sidang', name: 'tanggal_sidang', type: 'num', render: {
                    _: 'display',
                    sort: 'datetime'
                }, "width": "120px"},
                { data: 'nilai', name: 'nilai', "width": "100px", orderable: false, searchable: false },
                { data: 'status', name: 'status', "width": "200px" }
            ]
        });

        $('#tanggal_sidang').datepicker({
            format: 'd-M-yyyy'
        }).on('keyup', function(e) {
            if(e.keyCode == 8 || e.keyCode == 46) {
                $(this).val('');
                $(this).change();
            }
        })
        .on('change', function() {
            var i = $(this).attr('data-column');
            var v = $(this).val();
            dtBeritaAcaraSkripsi.columns(i).search(v).draw();
        });

        $('.search-input-text').on('keyup click', function() {
            var i = $(this).attr('data-column');
            var v = $(this).val();
            dtBeritaAcaraSkripsi.columns(i).search(v).draw();
        });

        $('.search-input-select').on('change', function() {
            var i = $(this).attr('data-column');
            var v = $(this).val();
            dtBeritaAcaraSkripsi.columns(i).search(v).draw();
        });

        $('#status').change();
    });
</script>
@endpush