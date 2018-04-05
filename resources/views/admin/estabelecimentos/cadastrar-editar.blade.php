@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', 'Meus Estabelecimentos' => 'admin.estab', $title => 'admin.estab.cadastro'), 'glyphicon glyphicon-home') !!}
@stop

@section('content')

@if (isset($estabelecimento))

    <!--Deletar imagem-->
        @if($estabelecimento->image != null)
            {!! Form::open(["route" => "admin.estab.image.destroy", "id" => "form-delete-image" ]) !!}
            {!! Form::hidden("id", "$estabelecimento->id") !!}
            {!! Form::close() !!}
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-image" title="Deletar imagem"><span class='glyphicon glyphicon-trash'></span></button>
            {!! App\Http\Controllers\Vendor::addModalView("Ação Irreversível!", "Deletar imagem?", "danger", "modal-delete-image", "Cancelar", "form-delete-image") !!}
        @endif
    <!--/Deletar imagem-->
    
    {!! Form::model($estabelecimento, ["route" => ["admin.estab.editar", $estabelecimento->id], "class" => "form-normal", "method" => "put", "files" => true ]) !!}
    <div class="div-img" >
        <img id="div-img" class="img-rounded" src="{{ $estabelecimento->image != null ? url(config('constantes.DESTINO_IMAGE_ESTABELECIMENTO').'\\'.$estabelecimento->image) : url(config('constantes.DEFAULT_IMAGE_ESTABELECIMENTO')) }}" width="79" alt="Imagem Estabelecimento"/>
@else
    {!! Form::open(["route" => "admin.estab.cadastro", "class" => "form-normal", "files" => true ]) !!}
    <div class="div-img" >
        <img id="div-img" class="img-rounded" src="{{ url(config('constantes.DEFAULT_IMAGE_ESTABELECIMENTO')) }}" width="79" alt="Imagem Estabelecimento"/>
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
        <div class="form-group has-feedback col-xs-12 {{ $errors->has('nome') ? 'has-error' : '' }}" style="padding: 0px">
            {!! Form::text("nome", null, ["class" => "form-control", "placeholder" => trans('auth.nome'), "title" => trans('auth.nome'), "required" ]) !!}
            <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
            @if ($errors->has('nome'))
            <span class="help-block">
                <strong>{{ $errors->first('nome') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group has-feedback col-xs-12 {{ $errors->has('categoria_estabelecimento') ? 'has-error' : '' }}" style="padding: 0px">
            {!! Form::select("categoria_estabelecimento", $categoriasEstabelecimentos, null, ["class" => "form-control", "placeholder" => trans('auth.categoria'), "title" => trans('auth.categoria') ]) !!}
            @if ($errors->has('categoria_estabelecimento'))
            <span class="help-block">
                <strong>{{ $errors->first('categoria_estabelecimento') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group has-feedback col-xs-12 {{ $errors->has('descricao_estabelecimento') ? 'has-error' : '' }}" style="padding: 0px">
            {!! Form::textarea("descricao_estabelecimento", null, ["class" => "form-control", "placeholder" => trans('auth.descricao'), "rows" => "2", "title" => trans('auth.descricao') ]) !!}
            <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
            @if ($errors->has('descricao_estabelecimento'))
            <span class="help-block">
                <strong>{{ $errors->first('descricao_estabelecimento') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group has-feedback col-xs-12 {{ $errors->has('rua') ? 'has-error' : '' }}" style="padding: 0px">
            {!! Form::text("rua", null, ["class" => "form-control", "placeholder" => trans('auth.rua'), "title" => trans('auth.rua'), "required" ]) !!}
            <span class="glyphicon glyphicon-map-marker form-control-feedback"></span>
            @if ($errors->has('rua'))
            <span class="help-block">
                <strong>{{ $errors->first('rua') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group has-feedback col-xs-12 {{ $errors->has('numero') ? 'has-error' : '' }}" style="padding: 0px">
            {!! Form::text("numero", null, ["class" => "form-control", "placeholder" => trans('auth.numero'), "title" => trans('auth.numero'), "required" ]) !!}
            <span class="glyphicon glyphicon-map-marker form-control-feedback"></span>
            @if ($errors->has('numero'))
            <span class="help-block">
                <strong>{{ $errors->first('numero') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group has-feedback col-xs-12 {{ $errors->has('complemento') ? 'has-error' : '' }}" style="padding: 0px">
            {!! Form::text("complemento", null, ["class" => "form-control", "placeholder" => trans('auth.complemento'), "title" => trans('auth.complemento') ]) !!}
            <span class="glyphicon glyphicon-map-marker form-control-feedback"></span>
            @if ($errors->has('complemento'))
            <span class="help-block">
                <strong>{{ $errors->first('complemento') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group has-feedback col-xs-12 {{ $errors->has('bairro') ? 'has-error' : '' }}" style="padding: 0px">
            {!! Form::text("bairro", null, ["class" => "form-control", "placeholder" => trans('auth.bairro'), "title" => trans('auth.bairro'), "required" ]) !!}
            <span class="glyphicon glyphicon-map-marker form-control-feedback"></span>
            @if ($errors->has('bairro'))
            <span class="help-block">
                <strong>{{ $errors->first('bairro') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group has-feedback col-xs-6 {{ $errors->has('cpf') ? 'has-error' : '' }}" style="padding: 0px;">
            <div class="input-group">
                <span class="input-group-addon">
                    {!! Form::radio("groupCpf", "CPF", true, ["onchange" => "alterarCPFCNPJ('cnpj', 'cpf')" ]) !!}
                </span>
                {!! Form::text("cpf", null, ["id" => "cpf", "class" => "form-control", "placeholder" => trans('auth.cpf'), "title" => trans('auth.cpf') ]) !!}
                @if ($errors->has('cpf'))
                <span class="help-block">
                    <strong>{{ $errors->first('cpf') }}</strong>
                </span>
                @endif
                <span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
            </div>          
        </div>
        <div class="form-group has-feedback col-xs-6 {{ $errors->has('cnpj') ? 'has-error' : '' }}" style="padding: 0px;" >
            <div class="input-group">
                <span class="input-group-addon">
                    {!! Form::radio("groupCpf", "CNPJ", false, ["onchange" => "alterarCPFCNPJ('cpf', 'cnpj')" ]) !!}
                </span>
                {!! Form::text("cnpj", null, ["id" => "cnpj", "class" => "form-control", "placeholder" => trans('auth.cnpj'), "disabled" => "disabled", "title" => trans('auth.cnpj') ]) !!}
                @if ($errors->has('cnpj'))
                <span class="help-block">
                    <strong>{{ $errors->first('cnpj') }}</strong>
                </span>
                @endif
                <span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
            </div>  
        </div>
        <div class="form-group has-feedback col-xs-2 {{ $errors->has('ddd_telefone_fixo') ? 'has-error' : '' }}" style="padding: 0px">
            {!! Form::text("ddd_telefone_fixo", null, ["class" => "form-control", "placeholder" => trans('auth.ddd_telefone'), "title" => trans('auth.ddd_telefone') ]) !!}
            @if ($errors->has('ddd_telefone_fixo'))
            <span class="help-block">
                <strong>{{ $errors->first('ddd_telefone_fixo') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group has-feedback col-xs-10 {{ $errors->has('telefone_fixo') ? 'has-error' : '' }}" style="padding: 0px">
            <span class="glyphicon glyphicon-phone-alt form-control-feedback"></span>
            {!! Form::text("telefone_fixo", null, ["class" => "form-control", "placeholder" => trans('auth.telefone_fixo'), "title" => trans('auth.telefone_fixo') ]) !!}
            @if ($errors->has('telefone_fixo'))
            <span class="help-block">
                <strong>{{ $errors->first('telefone_fixo') }}</strong>
            </span>
            @endif
            <div class="input-group">
                <span class="input-group-addon">
                    {{ Form::checkbox('exibir_telefone_fixo_clientes', null, true) }}
                </span>
                <li class="list-group-item">{{ trans('auth.exibir_telefone') }}</li>
            </div>
        </div>
        <div class="col-xs-12" style="padding: 0px">
            <div class="form-group has-feedback col-xs-2 {{ $errors->has('ddd_telefone_celular') ? 'has-error' : '' }}" style="padding: 0px">
                {!! Form::text("ddd_telefone_celular", null, ["class" => "form-control", "placeholder" => trans('auth.ddd_telefone'), "title" => trans('auth.ddd_telefone') ]) !!}
                @if ($errors->has('ddd_telefone_celular'))
                <span class="help-block">
                    <strong>{{ $errors->first('ddd_telefone_celular') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group has-feedback col-xs-10 {{ $errors->has('telefone_celular') ? 'has-error' : '' }}" style="padding: 0px">
                {!! Form::text("telefone_celular", null, ["class" => "form-control", "placeholder" => trans('auth.telefone_celular'), "title" => trans('auth.telefone_celular') ]) !!}
                <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                @if ($errors->has('telefone_celular'))
                <span class="help-block">
                    <strong>{{ $errors->first('telefone_celular') }}</strong>
                </span>
                @endif
                <div class="input-group">
                    <span class="input-group-addon">
                        {{ Form::checkbox('exibir_telefone_celular_clientes', null, true) }}
                    </span>
                    <li class="list-group-item">{{ trans('auth.exibir_telefone') }}</li>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        {{ Form::checkbox('telefone_celular_is_whatsapp', null) }}
                    </span>
                    <li class="list-group-item">{{ trans('auth.telefone_celular_is_whatsapp') }}</li>
                </div>
            </div>
        </div>
        <div class="form-group col-xs-12" style="padding-left: 0px">
            @if (isset($estabelecimento))
                {!! Form::submit("Alterar", ["class" => "btn btn-success", "name" => "enviar"]) !!}
            @else
                {!! Form::submit("Cadastrar", ["class" => "btn btn-success", "name" => "enviar"]) !!}
            @endif
            <a class="btn btn-danger" href="{{ route('admin.estab') }}">Voltar</a>
        </div> 
    {!! Form::close() !!}
    
    <!--Scripts-->
    <script src="{{ asset('assets/js/previewImagemUploadEmDiv.js') }}"></script>
    <script src="{{ asset('assets/js/checarImageUploadExcedeLimite.js') }}"></script>
    <script type="text/javascript">
        function alterarCPFCNPJ(idInput1, idInput2) {
            document.getElementById(idInput1).value = '';
            document.getElementById(idInput1).disabled = true;
            document.getElementById(idInput2).disabled = false;
            document.getElementById(idInput2).focus();
        }
    </script>
    
    <!--Funções-->
    {!! App\Http\Controllers\Vendor::addModalView("Imagem inválida", "A imagem enviada Excede o limite de upload (2MB). Selecione outra ou reduza o tamanho desta.", "Entendi") !!}
    

    <!--<script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });
        });
    </script>-->
    @stop
