@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('auth.login_message') }}</p>
            {!! Form::open(["url" => config('adminlte.login_url', 'login'), "class" => "form-normal"]) !!}
                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    {!! Form::email("email", null, ["class" => "form-control", "placeholder" => trans('auth.email'), "required" ]) !!}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    {!! Form::password("password", [ "class" => "form-control", "placeholder" => trans('auth.password'), "required" ] ) !!}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="input-group">
                            {{ Form::checkbox('remember', null) }}{{ trans('auth.remember_me') }}
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        {!! Form::submit(trans('auth.sign_in'), ["class" => "btn btn-success btn-block btn-flat", "name" => "enviar"]) !!}
                    </div>
                    <!-- /.col -->
                </div>
            {!! Form::close() !!}
            <div class="auth-links">
                <a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}"
                   class="text-center"
                >{{ trans('auth.i_forgot_my_password') }}</a>
                <br>
                @if (config('adminlte.register_url', 'register'))
                    <a href="{{ url(config('adminlte.register_url', 'register')) }}"
                       class="text-center"
                    >{{ trans('auth.register_a_new_membership') }}</a>
                @endif
            </div>
            <!--Login Social-->
            <div class="form-group text-center">
                <label for="name" class="">Login usando</label>
                <div class="">
                    <a href="{{ url('login/facebook') }}" class="btn btn-social-icon btn-facebook"><i class="fa fa-facebook"></i></a>
                    <a href="{{ url('login/google') }}" class="btn btn-social-icon btn-google"><i class="fa fa-google-plus"></i></a>
                </div>
            </div>
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop

@section('adminlte_js')
<!--    <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });
        });
    </script>-->
    @yield('js')
@stop
