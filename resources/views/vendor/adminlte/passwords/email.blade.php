@extends('adminlte::master')

@section('adminlte_css')
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
            @include('includes.message-return')
            <p class="login-box-msg">{{ trans('auth.password_reset_message') }}</p>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            {!! Form::open(["url" => config('adminlte.password_email_url', 'password/email'), "class" => "form-normal"]) !!}
                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    {!! Form::email("email", null, ["class" => "form-control", "placeholder" => trans('auth.email')]) !!}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                {!! Form::submit(trans('auth.send_password_reset_link'), ["class" => "btn btn-primary btn-block btn-flat", "name" => "enviar"]) !!}
            {!! Form::close() !!}
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop

@section('adminlte_js')
    @yield('js')
@stop
