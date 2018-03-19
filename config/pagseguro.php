<?php

if(env('PAGSEGURO_ENVORINMENT') == 'sandbox'){
    return [
        'environment'                       => env('PAGSEGURO_ENVORINMENT'),
        'email'                             => env('PAGSEGURO_EMAIL'),
        'token'                             => env('PAGSEGURO_TOKEN'),
            'url_redirect_after_request'    => 'https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=',
            'url_checkout'                  => 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout',
            'url_transparent_session'       => 'https://ws.sandbox.pagseguro.uol.com.br/v2/sessions',
            'url_transparent_js'            => 'https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js',
            'url_payment_transparent'       => 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions',
            'url_notification'              => 'https://ws.sandbox.pagseguro.uol.com.br/v3/transactions/notifications',
            'url_notification_test_post'    => 'https://hookb.in/Z6OOpa5x',
    ];
}else{
    return [
        'environment'                       => env('PAGSEGURO_ENVORINMENT'),
        'email'                             => env('PAGSEGURO_EMAIL'),
        'token'                             => env('PAGSEGURO_TOKEN'),
            'url_redirect_after_request'    => 'https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=',
            'url_checkout'                  => 'https://ws.pagseguro.uol.com.br/v2/checkout',
            'url_transparent_session'       => 'https://ws.pagseguro.uol.com.br/v2/sessions',
            'url_transparent_js'            => 'https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js',
            'url_payment_transparent'       => 'https://ws.pagseguro.uol.com.br/v2/transactions',
            'url_notification'              => 'https://ws.pagseguro.uol.com.br/v3/transactions/notifications',
    ];
}

