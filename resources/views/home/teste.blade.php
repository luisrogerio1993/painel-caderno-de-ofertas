<?php 
    $listaNavegadores = ['MSIE', 'Firefox', 'Chrome', 'Safari'];
    $navegadorAtual = $_SERVER['HTTP_USER_AGENT'];
    foreach($listaNavegadores as $navegador){
        if(strrpos($navegadorAtual, $navegador)){
            $navegadorAtual = $navegador;
        }
        if(strrpos($navegadorAtual, 'Firefox')){
            echo 'Seu navegador atual é o '. $navegadorAtual .', para total compactibilidade com a plataforma recomendamos o Mozilla Firefox.';
            break;
        }
    }
    