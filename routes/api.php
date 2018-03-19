<?php

Route::group(['namespace' => 'Api'], function(){

    /*
     * WEB SERVICE
     */
    Route::group(['prefix' => 'v1', 'namespace' => 'V1', ], function(){
        /*
         * ÁREA PROTEGIDA
         */
        Route::group(['middleware' => 'jwt.auth',], function(){
            //perfil
            Route::post('perfil', 'ApiPerfilController@index');
            
            //comentarios
            Route::post('comentario', 'ApiComentariosAnuncioController@store');
            Route::put('comentario/{id}/editar', 'ApiComentariosAnuncioController@update');
            Route::delete('comentario/{id}/deletar', 'ApiComentariosAnuncioController@destroy');
        });
        
        /*
         * ÁREA NÃO PROTEGIDA
         */
        //comentarios
        Route::get('comentario/{id}', 'ApiComentariosAnuncioController@show');
        Route::post('comentario/search', 'ApiComentariosAnuncioController@search');
        
        //login
        Route::post('login', 'Auth\AuthApiController@authenticate');
        Route::post('login-refresh', 'Auth\AuthApiController@refreshToken');
        
        //categorias anuncios/estabelecimentos
        Route::get('categorias', 'ApiCategoriaEstabelecimentosController@index');
        
        //anuncios
        Route::get('anuncio', 'ApiAnunciosController@index');
        Route::get('anuncio/{id}', 'ApiAnunciosController@show');
        Route::post('anuncio/search', 'ApiAnunciosController@searchByNome');
        Route::post('anuncio/categoria/search', 'ApiAnunciosController@searchByCategory');
        
        //estabelecimentos
        Route::get('estabelecimento/{id}', 'ApiEstabelecimentoController@show');
        Route::post('estabelecimento/search', 'ApiEstabelecimentoController@searchByNome');
        Route::post('estabelecimento/categoria/search', 'ApiEstabelecimentoController@searchByCategory');
        
        //cliques anuncios
        Route::post('click-anuncio', 'ApiClicksAnuncioController@store');
    });
    
    /*
     * PAGSEGURO
     */
    Route::post('pagseguro-notification', 'ApiPagSeguroController@request');
});

Route::any('{catchall}', function() {
    return abort(404);
})->where('catchall', '.*');