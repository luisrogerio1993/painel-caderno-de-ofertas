@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', $title => 'admin.perfil.editar'), 'glyphicon glyphicon-user') !!}

@if(!auth()->user()->email_verificado)
    <div class="alert alert-warning"> 
        Um email lhe foi enviado, siga os passos contidos nele para <strong>verirficar e ativar sua conta.</strong><br>
        Não recebeu esse e-mail? Cheque em sua caixa de spam.<br>
        <strong>Caso precise, reenvie o e-mail de verificação</strong> no botão baixo.<br><br>
        <a href="{{ route('admin.reenviar.confirmacao.email', auth()->user()->id) }}" class="btn btn-warning btn-block" type="button">Reenviar e-mail</a>
    </div>
@endif
@stop

@section('content')
@if(isset($perfil))

    <!--Deletar imagem-->
    @if($perfil->image != null)
        {!! Form::open(["route" => "admin.perfil.image.destroy", "id" => "form-delete-image" ]) !!}
        {!! Form::close() !!}
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-image" title="Deletar imagem"><span class='glyphicon glyphicon-trash'></span></button>
        {!! App\Http\Controllers\Vendor::addModalView("Ação Irreversível!", "Deletar imagem?", "danger", "modal-delete-image", "Cancelar", "form-delete-image") !!}
    @endif
    <!--/Deletar imagem-->

    {!! Form::model($perfil, ["route" => "admin.perfil.editar", "class" => "form-normal", "method" => "put", "files" => true]) !!}
    <div class="div-img" >
        <img id="div-img" class="img-rounded" src="{{ $perfil->image != null ? url(config('constantes.DESTINO_IMAGE_USUARIO').'\\'.$perfil->image) : url(config('constantes.DEFAULT_IMAGE_USUARIO')) }}" width="79" alt="Imagem Perfil"/>
@else

    <!--Deletar imagem-->
    @if($user->image != null)
        {!! Form::open(["route" => "admin.user.image.destroy", "id" => "form-delete-image" ]) !!}
        {!! Form::hidden("id", "$user->id") !!}
        {!! Form::close() !!}
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-image" title="Deletar imagem"><span class='glyphicon glyphicon-trash'></span></button>
        {!! App\Http\Controllers\Vendor::addModalView("Ação Irreversível!", "Deletar imagem?", "danger", "modal-delete-image", "Cancelar", "form-delete-image") !!}
    @endif
    <!--/Deletar imagem-->

    {!! Form::model($user, ["route" => ["admin.user.editar", $user->id], "class" => "form-normal", "method" => "put", "files" => true]) !!}
    <div class="div-img" >
        <img id="div-img" class="img-rounded" src="{{ $user->image != null ? url(config('constantes.DESTINO_IMAGE_USUARIO').'\\'.$user->image) : url(config('constantes.DEFAULT_IMAGE_USUARIO')) }}" width="79" alt="Imagem Perfil"/>
@endif
    </div>
    <div class="form-group has-feedback col-xs-10 {{ $errors->has('image') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::file("image", ["class" => "form-control", "id" => "image", "onchange" => "PreviewImage(this.id);"]) !!}
        <span class="glyphicon glyphicon-picture form-control-feedback"></span>
        @if ($errors->has('image'))
        <span class="help-block">
            <strong>{{ $errors->first('image') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group has-feedback col-xs-12 {{ $errors->has('name') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::text("name", null, ["class" => "form-control", "placeholder" => trans('auth.full_name'), "title" => trans('auth.full_name'), "required"]) !!}
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        @if ($errors->has('name'))
        <span class="help-block">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group has-feedback col-xs-12 {{ $errors->has('email') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::email("email", null, ["class" => "form-control", "placeholder" => trans('auth.email'), "title" => trans('auth.email'), "required" ]) !!}
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
        @endif
    </div>
    @if(isset($perfil))
        <div class="form-group has-feedback col-xs-12 {{ $errors->has('password') ? 'has-error' : '' }}" style="padding: 0px">
            @if( auth()->user()->conta_vinculada != 0 )
                <input class="form-control" placeholder="{{ trans('auth.password') }}" title="{{ trans('auth.password') }}" required name="password" type="password" value="{{ str_random(20) }}">
            @else
                {!! Form::password("password", [ "class" => "form-control", "placeholder" => trans('auth.password'), "title" => trans('auth.password'), "required" ]) !!}
            @endif
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
        </div>
    @endif
    <div class="form-group has-feedback col-xs-12 {{ $errors->has('cep') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::text("cep", null, ["class" => "form-control", "placeholder" => trans('auth.cep'), "title" => trans('auth.cep'), "required"]) !!}
        <span class="glyphicon glyphicon-map-marker form-control-feedback"></span>
        @if ($errors->has('cep'))
        <span class="help-block">
            <strong>{{ $errors->first('cep') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group">
        {!! Form::submit("Atualizar", ["class" => "btn btn-success", "name" => "enviar"]) !!}
        <a class="btn btn-danger" href="{{ route('admin.home') }}">Voltar</a>
    </div> 
{!! Form::close() !!}
<hr />
<div class="alert alert-danger"> 
    <strong>Excluir minha conta:</strong><br><br>
    <button class="btn btn-danger" type="button" onclick="$('#modal-info').modal('show');">Excluir conta</button>
</div>

<!--Modal excluir conta-->
<div class="modal modal-danger fade" id="modal-info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">Excluir conta</h4>
            </div>
            <div class="modal-body">
                <p>Sua conta será excluida permanentimente, assim como, todo os dados relacionados a ela em nosso sistema. Essa ação é irreversível!</p>
            </div>
            <div class="modal-footer">
                {!! Form::open(["route" => ["admin.perfil.deletar"], "method" => "DELETE" ]) !!}
                    {!! Form::button("Proceguir", ["type" => "submit", "class" => "btn btn-outline pull-right",  ]) !!}
                {!! Form::close() !!}
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal" >Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!--Scripts-->
<script src="{{ asset('assets/js/previewImagemUploadEmDiv.js') }}"></script>
@stop
