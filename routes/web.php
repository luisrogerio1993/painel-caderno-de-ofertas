<?php

/*
 * FAZER TESTE EM TODAS OS FORMULARIOS
 */

Route::group(['middleware' => ['auth', 'throttle:30,2',], 'namespace' => 'Admin', 'prefix' => 'admin'], function(){
    Route::get('/', 'AdminController@index')->middleware('userCadastroCompleto')->name('admin.home');
    Route::get('/ajuda', 'AdminController@ajuda')->name('admin.ajuda');
    Route::get('/reenviar-confirmacao-email/{id}', 'AdminController@sendConfirmationEmail')->name('admin.reenviar.confirmacao.email');
    Route::get('/contato', 'AdminController@contato')->name('admin.contato');
    
    /*
     * ANUNCIOS
     */
    Route::group(['middleware' => ['userCadastroCompleto','anuncioModify'], 'prefix' => 'anuncios', 'namespace' => 'Anuncios'], function(){ 
        Route::get('/', 'AnuncioController@index')->name('admin.anuncio');
        Route::get('/cadastro', 'AnuncioController@create')->middleware('anunciosLimit')->name('admin.anuncio.cadastro');
        Route::post('/cadastro', 'AnuncioController@store')->middleware('anunciosLimit')->name('admin.anuncio.cadastro');
        Route::get('/{id}/editar', 'AnuncioController@edit')->name('admin.anuncio.editar');
        Route::put('/{id}/editar', 'AnuncioController@update')->name('admin.anuncio.editar');
        Route::delete('/{id}/deletar', 'AnuncioController@destroy')->name('admin.anuncio.deletar');
        Route::post('/search', 'AnuncioController@search')->name('admin.anuncio.search');
        Route::post('/', 'AnuncioController@destroyImage')->name('admin.anuncio.image.destroy');
        
        /*
        * CLICKS ANUNCIO
        */
       Route::group(['middleware' => ['isAdmin']], function(){ 
           Route::get('/clicks', 'AnuncioController@clicksAnuncios')->name('admin.anuncio.clicks');
       });
    });
    
    /*
     * ESTABELECIMENTOS
     */
    Route::group(['middleware' => ['userCadastroCompleto', 'estabelecimentoModify',], 'prefix' => 'estabelecimentos', 'namespace' => 'Estabelecimentos'], function(){ 
        Route::get('/', 'EstabelecimentoController@index')->name('admin.estab');
        
        Route::group(['middleware' => ['estabelecimentoModify',]] , function(){ 
            Route::get('/cadastro', 'EstabelecimentoController@create')->middleware('estabelecimentosLimit')->name('admin.estab.cadastro');
            Route::post('/cadastro', 'EstabelecimentoController@store')->middleware('estabelecimentosLimit')->name('admin.estab.cadastro');
            Route::get('/{id}/editar', 'EstabelecimentoController@edit')->name('admin.estab.editar');
            Route::put('/{id}/editar', 'EstabelecimentoController@update')->name('admin.estab.editar');
            Route::delete('/{id}/deletar', 'EstabelecimentoController@destroy')->name('admin.estab.deletar');
            Route::post('/search', 'EstabelecimentoController@search')->name('admin.estab.search');
            Route::post('/', 'EstabelecimentoController@destroyImage')->name('admin.estab.image.destroy');
        });
    });
    /*
     * PERFIL
     */
    Route::group(['prefix' => 'perfil', 'namespace' => 'Perfil'], function(){ 
        Route::get('/', 'PerfilController@edit')->name('admin.perfil.editar');
        Route::put('/', 'PerfilController@update')->name('admin.perfil.editar');
        Route::delete('/deletar', 'PerfilController@destroy')->name('admin.perfil.deletar');
        Route::get('/info', 'PerfilController@info')->middleware('isAnunciante')->name('admin.perfil.info');
        Route::post('/', 'PerfilController@destroyImage')->middleware('perfilImageModify')->name('admin.perfil.image.destroy');
    });
    
    /*
     * CONFIGURAÇÃO
     */
    Route::group(['middleware' => ['isAdmin','userCadastroCompleto',], 'prefix' => 'configuracoes', 'namespace' => 'Configuracao'], function(){ 
        Route::get('/', 'ConfiguracaoController@edit')->name('admin.config.editar');
        Route::put('/', 'ConfiguracaoController@update')->name('admin.config.editar');
    });
    
    /*
     * ADMINISTRAÇÃO USUARIOS
     */
    Route::group(['middleware' => ['isAdmin','userCadastroCompleto'], 'prefix' => 'users', 'namespace' => 'Users'], function(){ 
        Route::get('/', 'UserController@index')->name('admin.user');
        Route::get('/{id}/editar', 'UserController@edit')->name('admin.user.editar');
        Route::put('/{id}/editar', 'UserController@update')->name('admin.user.editar');
        Route::delete('/{id}/deletar', 'UserController@destroy')->name('admin.user.deletar');
        Route::post('/search', 'UserController@search')->name('admin.user.search');
        Route::post('/', 'UserController@destroyImage')->name('admin.user.image.destroy');
    });
    
    /*
     * COMENTARIOS ANUNCIOS
     */
    Route::group(['middleware' => ['isAdmin','userCadastroCompleto'], 'prefix' => 'comentario', 'namespace' => 'ComentariosAnuncios'], function(){ 
        Route::get('/', 'ComentariosAnunciosController@index')->name('admin.comentario');
        Route::get('/{id}/editar', 'ComentariosAnunciosController@edit')->name('admin.comentario.editar');
        Route::put('/{id}/editar', 'ComentariosAnunciosController@update')->name('admin.comentario.editar');
        Route::delete('/{id}/deletar', 'ComentariosAnunciosController@destroy')->name('admin.comentario.deletar');
        Route::post('/search', 'ComentariosAnunciosController@search')->name('admin.comentario.search');
    });
    
    /*
     * FINANCEIRO
     */
    Route::group(['middleware' => ['userCadastroCompleto', 'financeiroShow'], 'prefix' => 'financeiro', 'namespace' => 'Financeiro'], function(){ 
        Route::get('/', 'FinanceiroController@index')->name('admin.financeiro');
        Route::get('/historico', 'FinanceiroController@show')->name('admin.financeiro.historico');
        Route::get('/compra/{id}', 'FinanceiroController@detalhesCompra')->name('admin.financeiro.detalhesCompra');
        Route::post('/select-method-payment', 'FinanceiroController@selectMethodPayment')->name('admin.financeiro.selectMethodPayment');
    });
    
    /*
     * PAGAMENTOS-PAGSEGURO
     */
    Route::post('/pagseguro-getcode', 'Pagamentos\PagseguroController@getCode')->name('admin.pagseguro.transparent.code');
    Route::post('/pagseguro-billet', 'Pagamentos\PagseguroController@billetTransaction')->name('admin.pagseguro.transparent.billet');
    Route::post('/pagseguro-card', 'Pagamentos\PagseguroController@cardTransaction')->name('admin.pagseguro.transparent.cardTransaction');
}); 

    /*
     * VERIFICAR VALIDADE DE ANUNCIOS E USUARIOS
     */
    
    Route::group([], function(){
        Route::get('/admin/rotina-diaria', 'Admin\AdminController@rotinaDiaria');
    });

    /*
     * HOME (Fora do Painel)
     */
    Route::get('/', 'Home\HomeController@index')->name('site.home');

    /*
     * ROTAS DE AUTENTIFICAÇÃO (login | register | rec password)
     */
     Auth::routes();

    
    Route::get('/register/verify/{email_token}', 'Admin\AdminController@verificarEmail')->name('admin.verificar.email');
    
    /*
     * ROTAS DE AUTENTIFICAÇÃO SOCIAL
     */
    
    Route::get('/login/{social}','Auth\LoginController@socialLogin')->where('social','facebook|google');
    Route::get('/login/{social}/callback','Auth\LoginController@handleProviderCallback')->where('social','facebook|google');
    
    /*
     * PARA TESTES
     */
    Route::get('/t', 'Home\HomeController@teste');
    
    Route::any('{catchall}', function() {
        return redirect()->route('site.home')->with('messageReturn', ['status' => false, 'messages' => ['Página não encontrada.',]]);
    })->where('catchall', '.*');
