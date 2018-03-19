<html>
    <head>
        <title>Card Pagseguro</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <h1>Pagar com cartão</h1>
            {!! Form::open(["id" => "form"]) !!}
            <div class="form-group">
                <label>Número do cartão</label>
                {!! Form::text("cardNumber", "4111111111111111", ["class" => "form-control", "placeholder" => "Número do cartão", "required" ]) !!}
            </div>
            <div class="form-group">
                <label>Mês de expiração</label>
                {!! Form::text("cardExpiryMonth", "12", ["class" => "form-control", "placeholder" => "Mês de expiração", "required" ]) !!}
            </div>
            <div class="form-group">
                <label>Ano de expiração</label>
                {!! Form::text("cardExpiryYear", "2030", ["class" => "form-control", "placeholder" => "Ano de expiração", "required" ]) !!}
            </div>
            <div class="form-group">
                <label>Código de segurança (3  números)</label>
                {!! Form::text("cardCVC", "123", ["class" => "form-control", "placeholder" => "Código de segurança (3  números)", "required" ]) !!}
            </div>
            <div class="form-group">
                {!! Form::hidden("cardName", null) !!}
                {!! Form::hidden("cardToken", null) !!}
                {!! Form::submit("enviar", ["class" => "btn btn-default btn-buy"]) !!}
            </div>
            {!! Form::close() !!}
        </div>
        <div class="preloader" style="display: none;">
            Carregando...
        </div>

        <!--jQuery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!--Checkout transparent-->
        <script src="{{ config('pagseguro.url_transparent_js_sandbox') }}"></script>
        <script>
            $(function(){
                setSessionId();
                
                $('#form').submit(function(){
                    getBrand();
                    
                    startPreloader("Enviado dados...");
                    return false;
                });
            });

            function setSessionId() {
                var data = $('#form').serialize();
                $.ajax({
                    url: "{{ route('admin.pagseguro.transparent.code') }}",
                    method: "POST",
                    data: data,
                    beforeSend: startPreloader()
                }).done(function (data) {
                    PagSeguroDirectPayment.setSessionId(data);
                }).fail(function () {
                    alert('Falha no request');
                }).always(function(){
                    endPreloader();
                });
            }
            
            function getBrand(){
                PagSeguroDirectPayment.getBrand({
                    cardBin: $('input[name=cardNumber]').val().replace(/ /g, ''),
                    success: function (response){
                        console.log("Success GetBrand");
                        console.log(response);
                        
                        $('input[name=cardName]').val(response.brand.name);
                        createCredCardToken();
                    },
                    error: function(response) {
                        console.log("Error GetBrand");
                        console.log(response);
                    },
                    complete: function(response){
                        console.log("Complete GetBrand");
                        endPreloader();
                    }
                });
            }
            
            function createCredCardToken(){
                PagSeguroDirectPayment.createCardToken({
                    cardNumber: $('input[name=cardNumber]').val().replace(/ /g, ''),
                    brand: $('input[name=cardName]').val(),
                    cvv: $('input[name=cardCVC]').val(),
                    expirationMonth: $('input[name=cardExpiryMonth]').val(),
                    expirationYear: $('input[name=cardExpiryYear]').val(),
                    success: function (response){
                        console.log("Success createCredCardToken");
                        console.log(response);
                        $('input[name=cardToken]').val(response.card.token);
                        
                        createTransactionCard();
                    },
                    error: function(response) {
                        console.log("Error createCredCardToken");
                        console.log(response);
                    },
                    complete: function(response){
                        console.log("Complete createCredCardToken");
                        //console.log(response);
                    }
                });
            }
            
            function createTransactionCard(){
                var senderHash = PagSeguroDirectPayment.getSenderHash();
                var data = $('#form').serialize()+"&senderHash="+senderHash;;
                $.ajax({
                    url: "{{ route('admin.pagseguro.transparent.cardTransaction') }}",
                    method: "POST",
                    data: data,
                    beforeSend: startPreloader("Realizando pagamento com cartão.")
                }).done(function (data) {
                    alert(data);
                    console.log(data);
                    //PagSeguroDirectPayment.setSessionId(data);
                }).fail(function () {
                    alert('Falha no request');
                }).always(function(){
                    endPreloader();
                });
            }
            
            function startPreloader(msgPreloader) {
                if(msgPreloader != ""){
                    $('.preloader').html(msgPreloader);
                }
                $('.preloader').show();
                $('.btn-buy').addClass('disabled');
            }
            
            function endPreloader() {
                $('.preloader').hide();
                $('.btn-buy').removeClass('disabled');
            }
        </script>
    </body>
</html>
