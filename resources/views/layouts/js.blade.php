{!! Html::script('assets/plugins/jquery/dist/jquery.min.js') !!} {{-- version 3.3.1 --}}
{!! Html::script('assets/plugins/jquery/dist/jquery.js') !!} {{-- version 3.3.1 --}}
{!! Html::script('assets/plugins/jquery-ui/jquery-ui.min.js') !!} {{-- version 1.11.4 --}}
{!! Html::script('assets/plugins/datatables.net/js/jquery.dataTables.min.js') !!} {{-- version 3.3.1 --}}
{!! Html::script('assets/plugins/datatables.net-bs/js/dataTables.bootstrap.min.js') !!} {{-- version 3.3.1 --}}
{!! Html::script('assets/plugins/bootstrap/dist/js/bootstrap.min.js') !!}
{!! Html::script('assets/plugins/select2/dist/js/select2.full.min.js') !!}
{!! Html::script('assets/plugins/moment/min/moment.min.js') !!}
{!! Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') !!}
{!! Html::script('assets/js/adminlte.min.js') !!}
<!-- iCheck -->
{!! Html::script('assets/plugins/iCheck/icheck.min.js') !!}
<!-- datetime picker -->
{!! Html::script('assets/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}

<script type="text/javascript">
 	$.widget.bridge('uibutton', $.ui.button);
    //Date picker
    $(document).on('focus', ".datepicker-me-class", function() {
	  $(this).datepicker({
	      format: 'dd MM yyyy',
	      autoclose: true,
	      todayHighlight: true,
	      todayBtn: 'linked'
	  });
	});
</script>