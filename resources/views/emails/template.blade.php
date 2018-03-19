<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <style type="text/css">
            body{
                font-family: Avenir, Helvetica, sans-serif;
                line-height: 1.4;
                margin: 0;
            }

            .div-vermelha
            {
                font-weight: bold;
                text-align: center;
                color: #ffffff;
                background-color: #dd4b39;
                margin: 0px;
                padding: 1px 0px;
            }

            .div-ajuda-link
            {
                color: #9d9d9d;
                background-color: #f3f3f3;
                padding-top: 1px;
                padding-bottom: 1px;
                text-align: center;
            }

            .div-conteudo
            {
                margin-left: 15px;
                margin-right: 15px;
                font-weight: bold;
                color: #525252;
            }
            .botao-email {
                -moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
                -webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
                box-shadow:inset 0px 1px 0px 0px #ffffff;
                background-color:transparent;
                border-radius: 5px;
                text-indent:0;
                border:1px solid #dcdcdc;
                display:inline-block;
                color:#666666;
                font-size:14px;
                font-weight:bold;
                font-style:normal;
                height:40px;
                line-height:40px;
                width:auto;
                text-decoration:none;
                text-align:center;
                text-shadow:1px 1px 0px #ffffff;
                padding-left: 10px;
                padding-right: 10px;
            }.botao-email:active {
                position:relative;
                top:1px;
            }
        </style>
        <div class="container-fluid">
            <div class="row div-vermelha">
                <h1>{{ env('APP_NAME', 'Caderno de Ofertas') }}</h1>
            </div>
            <div class="row div-conteudo">
                @yield('conteudo')
            </div>
            <div class="row div-vermelha">
                <p>Atenciosamente, equipe {{ env('APP_NAME', 'Caderno de Ofertas') }}.<br />© 2018 {{ env('APP_NAME', 'Caderno de Ofertas') }}. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>