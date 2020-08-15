<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $title }}
    <small>{{ $sub_title }}</small>
  </h1>
  <ol class="breadcrumb">
    @for ($i = 0; $i < count($breadcrumbs); $i++)
        <li>
            <a href="javascript:;">
                @if ($i == 0)
                    <i class="fa fa-dashboard"></i> 
                @endif
                {{ $breadcrumbs[$i] }}
            </a>
        </li>
    @endfor
  </ol>
</section>

@if (Session::has('message'))
    <div class="m-b-15 m-l-15 m-r-15 alert alert-fadeOut alert-{{ Session::get('alert-type', 'info') }} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info-circle"></i> Pemberitahuan!</h4>
        {{ Session::get('message') }}
    </div>
@endif

@if (Session::has('messages'))
    @foreach (Session::get('messages') as $messageKey)
        <div class="m-b-15 m-l-15 m-r-15 alert alert-fadeOut alert-{{ $messageKey['alert-type'] or 'info' }} alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info-circle"></i> Pemberitahuan!</h4>
            {{ $messageKey['message'] }}
        </div>
    @endforeach
@endif

@if (count($errors))
    <div class="m-b-15 m-l-15 m-r-15 alert alert-fadeOut alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <strong>Whoops!</strong> Ada beberapa masalah dengan masukan Anda.
        {{ Session::get('message') }}
        <ul>
            @foreach($errors->all() as $error)
            <strong><li>{{ $error }}</li></strong>
            @endforeach
        </ul>
    </div>
@endif

{{-- Delete Confrimation Modal --}}
<form action="" method="POST" class="delete-confirmation-modal-form">
    <div id="delete-confirmation-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="delete-confirmation-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close remove-data-from-delete-form" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="delete-confirmation-modalLabel">Konfirmasi Penghapusan</h4>
                </div>
                <div class="modal-body">
                    {{-- <h4>Are you sure you want to delete this record?</h4> --}}
                    <h4>Apakah anda yakin untuk menghapus data ini?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left remove-data-from-delete-form" data-dismiss="modal">
                        <span class='fa fa-times'></span> Close
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <span class='fa fa-trash-o'></span> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Accept request Confrimation Modal --}}
<form action="" method="POST" class="accept-confirmation-modal-form">
    <div id="accept-confirmation-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="accept-confirmation-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close remove-data-from-accept-form" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="accept-confirmation-modalLabel">Konfirmasi Penerimaan Pendaftaran</h4>
                </div>
                <div class="modal-body">
                    {{-- <h4>Are you sure you want to approve this request?</h4> --}}
                    <h4>Apakah anda yakin untuk menerima pendaftaran ini?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left remove-data-from-accept-form" data-dismiss="modal">
                        <span class='fa fa-times'></span> Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <span class='fa fa-check'></span> Terima
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Finance Accept request Confrimation Modal --}}
<form action="" method="POST" class="finance-accept-confirmation-modal-form">
    <div id="finance-accept-confirmation-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="finance-accept-confirmation-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close remove-data-from-finance-accept-form" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="accept-confirmation-modalLabel">Konfirmasi Tagihan Keuangan Mahasiwa Lunas</h4>
                </div>
                <div class="modal-body">
                    {{-- <h4>Are you sure you want to approve this request?</h4> --}}
                    <h4>Apakah anda yakin untuk melakukan validasi sukses pada tagihan keuangan mahasiswa berikut ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left remove-data-from-accept-form" data-dismiss="modal">
                        <span class='fa fa-times'></span> Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <span class='fa fa-check'></span> Terima
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Reject request Confrimation Modal --}}
<form action="" method="POST" class="reject-confirmation-modal-form">
    <div id="reject-confirmation-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="reject-confirmation-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close remove-data-from-reject-form" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="reject-confirmation-modalLabel">Konfirmasi Penolakan Pendaftaran</h4>
                </div>
                <div class="modal-body">
                    {{-- <h4>Are you sure you want to reject this request?</h4> --}}
                    <h4>Apakah anda yakin untuk menolak pendaftaran ini?</h4>

                    <br>
                    {{ Form::label('reject_reason', 'Alasan ditolaknya pendaftaran') }}
                    {{ Form::textarea('reject_reason', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'size' => '30x5')) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left remove-data-from-reject-form" data-dismiss="modal">
                        <span class='fa fa-times'></span> Close
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <span class='fa fa-times'></span> Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Finance Reject request Confrimation Modal --}}
<form action="" method="POST" class="finance-reject-confirmation-modal-form">
    <div id="finance-reject-confirmation-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="finance-reject-confirmation-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close remove-data-from-finance-reject-form" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="finance-reject-confirmation-modalLabel">Konfirmasi Penolakan Validasi Keuangan</h4>
                </div>
                <div class="modal-body">
                    {{-- <h4>Are you sure you want to reject this request?</h4> --}}
                    <h4>Apakah anda yakin untuk menolak validasi keuangan ini?</h4>

                    <br>
                    {{ Form::label('reject_reason', 'Alasan ditolaknya') }}
                    {{ Form::textarea('reject_reason', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'size' => '30x5')) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left remove-data-from-finance-reject-form" data-dismiss="modal">
                        <span class='fa fa-times'></span> Close
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <span class='fa fa-times'></span> Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Cancel request Confirmation Modal --}}
<form action="" method="POST" class="cancel-confirmation-modal-form">
    <div id="cancel-confirmation-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="cancel-confirmation-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close remove-data-from-cancel-form" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="cancel-confirmation-modalLabel">Konfirmasi Pembatalan Sidang</h4>
                </div>
                <div class="modal-body">
                    {{-- <h4>Are you sure you want to cancel this request?</h4> --}}
                    <h4>Apakah anda yakin untuk membatalkan sidang ini?</h4>

                    <br>
                    {{ Form::label('cancel_reason', 'Alasan dibatalkan sidang') }}
                    {{ Form::textarea('cancel_reason', null, array('class' => 'form-control', 'placeholder' => 'Kosong...', 'size' => '30x5')) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left remove-data-from-cancel-form" data-dismiss="modal">
                        <span class='fa fa-times'></span> Close
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <span class='fa fa-times'></span> Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Update Request Status Modal --}}
<form action="" method="POST" class="update-request-status-confirmation-modal-form">
    <div id="update-request-status-confirmation-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="update-request-status-confirmation-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close remove-data-from-update-request-status-form" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="update-request-status-confirmation-modalLabel">Konfirmasi Pengubahan Status Pendaftaran</h4>
                </div>
                <div class="modal-body">
                    <span class="kp-request-span text-red" style="display: none;">Silahkan serahkan berkas berikut ke BAAK dalam batas waktu 2 hari:</span>
                    <span class="skripsi-tesis-request-span text-red" style="display: none;">Silahkan serahkan berkas berikut ke BAAK setelah tervalidasi oleh pihak Finance dalam batas waktu 2 hari:</span>
                    <div class="kp-request-requirement" style="display: none;">
                        <span class="text-red">2 Laporan Hardcover Kerja Praktek untuk disaat sidang.</span>
                        <!-- <span class="text-red">1. Persetujuan Sidang KP</span><br>
                        <span class="text-red">2. Form Bimbingan KP</span> -->
                    </div>

                    <div class="skripsi-request-requirement" style="display: none;">
                        <!-- <span class="text-red">1. Form yang di download di bagian pendaftaran skripsi</span><br>
                        <span class="text-red">2. Persetujuan Sidang Skripsi</span><br>
                        <span class="text-red">3. Kartu Bimbingan Skripsi</span><br>
                        <span class="text-red">4. Lembar Pernyataan Anti Plagiat</span><br> -->
                        <span class="text-red">1. Hardcopy Laporan</span><br>
                        <span class="text-red">2. Pernyataan beda identitas jika ada perbedaan data nama atau tempat tanggal lahir</span>
                    </div>

                    <div class="tesis-request-requirement" style="display: none;">
                        <!-- <span class="text-red">1. Form yang di download di bagian pendaftaran skripsi</span><br>
                        <span class="text-red">2. Persetujuan Sidang Tesis</span><br>
                        <span class="text-red">3. Kartu Bimbingan Tesis</span><br>
                        <span class="text-red">4. Lembar Pernyataan Anti Plagiat</span><br> -->
                        <span class="text-red">1. Hardcopy Laporan</span><br>
                        <span class="text-red">2. Pernyataan beda identitas jika ada perbedaan data nama atau tempat tanggal lahir</span>
                    </div>

                    {{-- <h4>Are you sure you want to submit this request?</h4> --}}
                    <h4>Apakah anda yakin untuk menyerahkan pendaftaran ini?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left remove-data-from-update-request-status-form" data-dismiss="modal">
                        <span class='fa fa-times'></span> Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class='fa fa-send'></span> Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Kirim Surat Undangan Modal --}}
<form action="" method="POST" class="update-penjadwalan-status-confirmation-modal-form">
    <div id="update-penjadwalan-status-confirmation-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="update-penjadwalan-status-confirmation-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close remove-data-from-update-penjadwalan-status-form" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                    <h4 class="modal-title" id="update-penjadwalan-status-confirmation-modalLabel">Konfirmasi Pengiriman Surat Undangan Sidang</h4>
                </div>
                <div class="modal-body">
                    <h4 id="type">Apakah anda yakin untuk mengirim surat undangan sidang kepada mahasiswa dan dosen yang berwewenang ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left remove-data-from-update-penjadwalan-status-form" data-dismiss="modal">
                        <span class="fa fa-times"></span> Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fa fa-send"></span> Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Give permission modal --}}
<form action="" method="POST" class="update-berita-acara-status-confirmation-modal-form">
    <div id="update-berita-acara-status-confirmation-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="update-berita-acara-status-confirmation-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close remove-data-from-update-berita-acara-status-form" data-dismiss="modal" arial-label="Close">
                        <span aria-hidden="true">x</span>                        
                    </button>
                    <h4 class="modal-title" id="update-berita-acara-status-confirmation-modalLabel">Konfirmasi Untuk Memberikan Akses Pengisian Form Berita Acara</h4>
                </div>
                <div class="modal-body">
                    <h4 id="type">Apakah anda yakin untuk memberikan akses pengisian form berita acara kepada pihak yang terlibat ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left remove-data-from-update-berita-acara-status-form" data-dismiss="modal">
                        <span class="fa fa-times"></span> Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fa fa-send"></span> Berikan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('custom_js')
    <script type="text/javascript">
        $(document).ready(function(){
            var kpRequestRequirement = $('.kp-request-requirement')[0];
            var skripsiRequestRequirement = $('.skripsi-request-requirement')[0];
            var tesisRequestRequirement = $('.tesis-request-requirement')[0];
            var kpRequestSpan = $('.kp-request-span')[0];
            var skripsiTesisRequestSpan = $('.skripsi-tesis-request-span')[0];

            // for give permission berita acara
            $(document).on('click', '.update-berita-acara-status-confirmation', function() {
                var url = $(this).attr('data-url');
                var type = $(this).attr('data-type');
                $('.update-berita-acara-status-confirmation-modal-form').attr('action', url);
                $('body').find('.update-berita-acara-status-confirmation-modal-form').append('<input name="_token" type="hidden" value="{{ csrf_token() }}">');
                $('body').find('.update-berita-acara-status-confirmation-modal-form h4#type').text('Apakah anda yakin untuk memberikan akses form berita acara ' + type + ' kepada pihak yang terlibat ?');
            });

            // For send invitation for sidang
            $(document).on('click', '.update-penjadwalan-status-confirmation', function() {
                var url = $(this).attr('data-url');
                var type = $(this).attr('data-type');
                $('.update-penjadwalan-status-confirmation-modal-form').attr('action', url);
                $('body').find('.update-penjadwalan-status-confirmation-modal-form').append('<input name="_token" type="hidden" value="{{ csrf_token() }}">');
                $('body').find('.update-penjadwalan-status-confirmation-modal-form h4#type').text('Apakah anda yakin untuk mengirim surat undangan sidang ' + type +' kepada mahasiswa dan dosen yang berwewenang ?');
            });

            $('.remove-data-from-update-penjadwalan-status-form').click(function() {
                $('body').find('.update-penjadwalan-status-confirmation-modal-form').find('input').remove();
            });

            // For A Delete Record Popup
            $(document).on('click', '.delete-confirmation', function () {
                var id = $(this).attr('data-id');
                var url = $(this).attr('data-url');
                $(".delete-confirmation-modal-form").attr("action",url);
                $('body').find('.delete-confirmation-modal-form').append('<input name="_token" type="hidden" value="{{ csrf_token() }}">');
                $('body').find('.delete-confirmation-modal-form').append('<input name="_method" type="hidden" value="DELETE">');
            });

            $('.remove-data-from-delete-form').click(function() {
                $('body').find('.delete-confirmation-modal-form').find("input").remove();
            });

            // For Reject Request Popup
            $(document).on('click', '.reject-confirmation', function () {
                var id = $(this).attr('data-id');
                var url = $(this).attr('data-url');
                $(".reject-confirmation-modal-form").attr("action",url);
                $('body').find('.reject-confirmation-modal-form').append('<input name="_token" type="hidden" value="{{ csrf_token() }}">');
            });

            $('.remove-data-from-reject-form').click(function() {
                $('body').find('.reject-confirmation-modal-form').find("input").remove();
            });
            
            // For finance reject request popup
            $(document).on('click', '.finance-reject-confirmation', function () {
                var id = $(this).attr('data-id');
                var url = $(this).attr('data-url');
                $(".finance-reject-confirmation-modal-form").attr("action",url);
                $('body').find('.finance-reject-confirmation-modal-form').append('<input name="_token" type="hidden" value="{{ csrf_token() }}">');
            });

            $('.remove-data-from-finance-reject-form').click(function() {
                $('body').find('.finance-reject-confirmation-modal-form').find("input").remove();
            });

            // For Cancel Request Popup
            $(document).on('click', '.cancel-confirmation', function() {
                var id = $(this).attr('data-id');
                var url = $(this).attr('data-url');
                $(".cancel-confirmation-modal-form").attr("action", url);
                $("body").find(".cancel-confirmation-modal-form").append('<input name="_token" type="hidden" value="{{ csrf_token() }}">')
            });

            $('.remove-data-from-cancel-form').click(function() {
                $('body').find('.cancel-confirmation-modal-form').find("input").remove();
            });

            // For Accept Request Popup
            $(document).on('click', '.accept-confirmation', function () {
                var id = $(this).attr('data-id');
                var url = $(this).attr('data-url');
                $(".accept-confirmation-modal-form").attr("action",url);
                $('body').find('.accept-confirmation-modal-form').append('<input name="_token" type="hidden" value="{{ csrf_token() }}">');
            });

            $('.remove-data-from-accept-form').click(function() {
                $('body').find('.accept-confirmation-modal-form').find("input").remove();
            });

            // For Finance Accept Request Popup
            $(document).on('click', '.finance-accept-confirmation', function () {
                var id = $(this).attr('data-id');
                var url = $(this).attr('data-url');
                $(".finance-accept-confirmation-modal-form").attr("action",url);
                $('body').find('.finance-accept-confirmation-modal-form').append('<input name="_token" type="hidden" value="{{ csrf_token() }}">');
            });

            $('.remove-data-from-finance-accept-form').click(function() {
                $('body').find('.finance-accept-confirmation-modal-form').find("input").remove();
            });

            // For A Update Record Status Popup
            $(document).on('click', '.update-request-status-confirmation', function () {
                var id = $(this).attr('data-id');
                var url = $(this).attr('data-url');
                var type = $(this).attr('data-type');
                var types = JSON.parse($(this).attr('data-types'));
                
                switch (Number(type)) {
                    case Number(getIndexFromArr(types, 'KP')):
                        kpRequestRequirement.style.display = 'block';
                        skripsiRequestRequirement.style.display = 'none';
                        tesisRequestRequirement.style.display = 'none';
                        kpRequestSpan.style.display = 'block';
                        skripsiTesisRequestSpan.style.display = 'none';
                        break;
                    case Number(getIndexFromArr(types, 'Skripsi')):
                        skripsiRequestRequirement.style.display = 'block';
                        kpRequestRequirement.style.display = 'none';
                        tesisRequestRequirement.style.display = 'none';
                        kpRequestSpan.style.display = 'none';
                        skripsiTesisRequestSpan.style.display = 'block';
                        break;
                    case Number(getIndexFromArr(types, 'Tesis')):
                        tesisRequestRequirement.style.display = 'block';
                        skripsiRequestRequirement.style.display = 'none';
                        kpRequestRequirement.style.display = 'none';
                        kpRequestSpan.style.display = 'none';
                        skripsiTesisRequestSpan.style.display = 'block';
                        break;
                    default:
                }

                $(".update-request-status-confirmation-modal-form").attr("action",url);
                $('body').find('.update-request-status-confirmation-modal-form').append('<input name="_token" type="hidden" value="{{ csrf_token() }}">');
            });

            $('.remove-data-from-update-request-status-form').click(function() {
                $('body').find('.update-request-status-confirmation-modal-form').find("input").remove();
            });

            // For prevent user entered not numeric value on phone number
            $(document).on('keypress', '.numeric-field', function ($event) {
                if (!isNumberKey($event)) {
                    $event.preventDefault();
                }
            });
        });

        // select2 options initialize
        $('.select2').select2({
            allowClear: 'true',
            placeholder: 'Pilih salah satu...'
        });

        function getIndexFromArr(arr, key) {
            if (arr instanceof Array) {
                return arr.indexOf(key);
            }

            return getKeyByValue(arr, key);
        }

        function getKeyByValue(obj, value) {
            for (var prop in obj) {
                if (obj.hasOwnProperty(prop)) {
                    if (obj[prop] === value) {
                        return prop;
                    }
                }
            }
        }

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;

            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;

            return true;
        }

        // timeout for disappearing alert popup => 5 seconds
        setTimeout(function () {
            $('.alert-fadeOut').fadeOut();
        }, 5000);
    </script>
@endpush