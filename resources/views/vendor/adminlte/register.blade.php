@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@stop

@section('body_class', 'register-page')

@section('body')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Caderno</b> de Ofertas') !!}</a>
        </div>
        <div class="register-box-body">
            @include('includes.message-return')
            <p class="login-box-msg">{{ trans('auth.register_message') }}</p>
            {!! Form::open(["url" => config('adminlte.register_url', 'register'), "class" => "form-normal", "files" => true]) !!}
                <div class="form-group has-feedback {{ $errors->has('image') ? 'has-error' : '' }}">
                    {!! Form::file("image", ["class" => "form-control"]) !!}
                    <span class="glyphicon glyphicon-picture form-control-feedback"></span>
                    @if ($errors->has('image'))
                        <span class="help-block">
                            <strong>{{ $errors->first('image') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('name') ? 'has-error' : '' }}">
                    {!! Form::text("name", null, ["class" => "form-control", "placeholder" => trans('auth.full_name'), "required"]) !!}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    {!! Form::email("email", null, ["class" => "form-control", "placeholder" => trans('auth.email'), "required"]) !!}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    {!! Form::password("password", [ "class" => "form-control", "placeholder" => trans('auth.password'), "required"]) !!}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    {!! Form::password("password_confirmation", [ "class" => "form-control", "placeholder" => trans('auth.retype_password'), "required"]) !!}
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('cep') ? 'has-error' : '' }}">
                    {!! Form::text("cep", null, ["class" => "form-control", "placeholder" => trans('auth.cep'), "required"]) !!}
                    <span class="glyphicon glyphicon-map-marker form-control-feedback"></span>
                    @if ($errors->has('cep'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cep') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
                    <center>
                        {!! Recaptcha::render() !!}
                    </center>
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="help-block">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </span>
                    @endif
                </div>
                {!! Form::submit(trans('auth.register'), ["class" => "btn btn-success btn-block btn-flat", "name" => "enviar"]) !!}
            {!! Form::close() !!}
            <div class="auth-links">
                <a href="{{ url(config('adminlte.login_url', 'login')) }}"
                   class="text-center">{{ trans('auth.i_already_have_a_membership') }}</a>
            </div>
            <!--Login Social-->
            <div class="form-group text-center">
                <label for="name" class="">Registrar usando</label>
                <div class="">
                    <a href="{{ url('login/facebook') }}" class="btn btn-social-icon btn-facebook"><i class="fa fa-facebook"></i></a>
                    <a href="{{ url('login/google') }}" class="btn btn-social-icon btn-google"><i class="fa fa-google-plus"></i></a>
                </div>
            </div>
        </div>
        <!-- /.form-box -->
    </div><!-- /.register-box -->
@stop

@section('adminlte_js')
    @yield('js')
@stop
