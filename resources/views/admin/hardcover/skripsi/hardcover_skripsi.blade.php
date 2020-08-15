@extends('layouts.master')

@section('content')
@include('shared.page_header')
<!-- Main Content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <!-- export hardcover skripsi file -->
                    {{ Form::open(array('url' => route('admin.hardcoverskripsi.download'))) }}
                        <div class="custom-add-new-button">
                            @if (Gate::allows('is-admin'))
                                <div class="button-top-upload-excel">
                                    <input type="hidden" id="txtStatus" name="txtStatus" value="{{ $defaultStatusSelection }}" readonly>
                                    {{ Form::button('<i class="fa fa-download"></i> Ekspor Melalui Excel', ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                </div>
                            @endif
                        </div>
                    {{ Form::close() }}    
                    <br>
                    <div class="table-responsive">
                        <table id="hardcover_skripsi_datatable" class="data-table table table-bordered table-hover">
                            <thead>
                                <tr role="row">
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Nama Mahasiswa</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">NPM</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Prodi</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Nama Pembimbing</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Tanggal Submit</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Tanggal Validasi</th>
                                    <th class="text-center vcenter" rowspan="1" colspan="1" width="120px">Status</th>
                                </tr>

                                <tr role="row" class="filter">
                                    <td>
                                        {{ Form::text('nama_mahasiswa', null, ['id' => 'nama_mahasiswa', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'data-column' => '0']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('npm', null, ['id' => 'npm', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'placeholder' => '', 'data-column' => '1']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('prodi', null, ['id' => 'prodi', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'placeholder' => '', 'data-column' => '2']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('nama_pembimbing', null, ['id' => 'nama_pembimbing', 'class' => 'width-100p form-control form-filter input-sm search-input-text', 'placeholder' => '', 'data-column' => '3']) }}
                                    </td>

                                    <td>
                                        {{ Form::text('tanggal_submit', null, ['id' => 'tanggal_submit', 'class' => 'width-100p form-control form-filter input-sm date-input', 'placeholder' => '', 'data-column' => '4', 'readonly' => true]) }}
                                    </td>

                                    <td>
                                        {{ Form::text('tanggal_validasi', null, ['id' => 'tanggal_validasi', 'class' => 'width-100p form-control form-filter input-sm date-input', 'placeholder' => '', 'data-column' => '5', 'readonly' => true]) }}
                                    </td>

                                    <td>
                                        {{ Form::select('status', $statusHardcover, $defaultStatusSelection, ['id' => 'status', 'class' => 'form-control form-filter input-sm select2 search-input-select', 'placeholder' => 'all', 'data-column' => '6']) }}
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
            var hardcover_skripsi_datatable = $('#hardcover_skripsi_datatable').DataTable(
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
                    url: '{{ route('admin.hardcover_skripsi.list') }}',
                    data: function(data) {
                        data._token = '{{ csrf_token() }}';
                    },
                    type: 'POST',
                },
                columns: [
                    {data: 'nama_mahasiswa', name: 'nama_mahasiswa'},
                    {data: 'npm', name: 'npm'},
                    {data: 'prodi', name: 'prodi'},
                    {data: 'nama_pembimbing', name: 'nama_pembimbing'},
                    {data: 'tanggal_submit', type: 'num', render: {
                            _: 'display',
                            sort: 'date'
                        }
                    },
                    {data: 'tanggal_validasi', type: 'num', render: {
                            _: 'display',
                            sort: 'date'
                        }
                    },
                    {data: 'status', name: 'status'},
                ]
            });

            $('.search-input-text').on('keyup click', function() { // for text boxes
                var i = $(this).attr('data-column'); // getting column index
                var v = $(this).val(); // getting search input value
                hardcover_skripsi_datatable.columns(i).search(v).draw();
            });  

            $('.search-input-select').on('change', function() {
                // set text box status hidden for export excel value
                $('#txtStatus').val($(this).val());

                var i = $(this).attr('data-column'); // getting column index
                var v = $(this).val(); // getting search input value
                hardcover_skripsi_datatable.columns(i).search(v).draw();
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
                hardcover_skripsi_datatable.columns(i).search(v).draw();
            });  

            $('#status').change();
        });
    </script>
@endpush