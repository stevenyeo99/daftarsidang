<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>UIB | Pendaftaran sidang KP dan Skripsi dan Tesis online</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <meta content="" name="description">
        <meta content="" name="author">
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        
        {!! Html::style('assets/plugins/bootstrapOld/css/bootstrap.min.css') !!}
        {!! Html::style('assets/plugins/font-awesome/css/font-awesome.min.css') !!}
        {!! Html::style('assets/css/AdminLTE.min.css') !!}
        {!! Html::style('assets/css/custom.css') !!}
        {!! Html::style('assets/plugins/Ionicons/css/ionicons.min.css') !!}
        <!-- iCheck -->
        {!! Html::style('assets/plugins/iCheck/square/blue.css') !!}
        <link rel="shortcut icon" href="./assets/img/Icon.png" />
    </head>

    <body class="hold-transition login-page" style="overflow-y: hidden;">
        <div class="login-box">
            <div class="login-logo">
                <a href="../../index2.html"><b>UIB</b></a>
            </div>

            <div class="login-box-body">
                <p class="login-box-msg">Sistem Online sidang KP/Skripsi/Tesis</p>

                <form action="{{ route('finance.login') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group has-feedback">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required autofocus>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        @if($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback">
                        <input id="password" type="password" class="form-control" placeholder="Password" name="password" placeholder="Password"  required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <div class="checkbox icheck">
                                <label class="">
                                    <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                    <input
                            type="checkbox"
                            name="remember"
                            {{ old('remember') ? 'checked' : '' }}
                            style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">
                            <ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                        </div> Ingat Saya
                                </label>
                            </div>
                        </div>

                        <div class="col-xs-6" style="padding-left: 0;">
                            <button type="submit" class="btn btn-primary btn-flat">Masuk Sebagai Finance</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {!! Html::script('assets/plugins/jquery/dist/jquery.min.js') !!}
        {!! Html::script('assets/plugins/bootstrap/dist/js/bootstrap.min.js') !!}
        {!! Html::script('assets/plugins/jquery-validation/js/jquery.validate.min.js') !!}
        {!! Html::script('assets/plugins/jquery-validation/js/additional-methods.min.js') !!}
        {{-- {!! Html::script('assets/plugins/backstretch/jquery.backstretch.min.js') !!} --}}
        {{-- {!! Html::script('assets/scripts/app.min.js') !!} --}}
        {{-- {!! Html::script('assets/scripts/login-5.min.js') !!} --}}
        <!-- iCheck -->
        {!! Html::script('assets/plugins/iCheck/icheck.min.js') !!}
        <script>
          $(function () {
            $('input').iCheck({
              checkboxClass: 'icheckbox_square-blue',
              radioClass: 'iradio_square-blue',
              increaseArea: '20%' /* optional */
            });
          });
        </script>
    </body>
</html>