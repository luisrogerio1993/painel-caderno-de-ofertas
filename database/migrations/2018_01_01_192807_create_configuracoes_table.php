<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracoes', function (Blueprint $table) {
            $table->increments('id');
            $table->double('valor_anuncio_padrao', 5, 2);
            $table->double('valor_anuncio_premium', 5, 2);
            $table->double('valor_anuncio_proximidade_fisica', 5, 2);
            $table->integer('limite_anuncios_anunciante')->default(5);
            $table->integer('limite_estabelecimentos_anunciante')->default(1);
            $table->text('texto_aviso')->nullable();
            $table->enum('mostrar_aviso_para', ['0', '1', '2', '3', '4'])->default(0)->comment('0 = Usuários / 1 = Anunciantes / 2 = Usuários e Anunciantes / 3 = Administradores / 4 = Todos');
            $table->enum('configuracao_aviso', ['0', '1', '2'])->default(0)->comment('0 = Desativada / 1 = Importante / 2 = Crítica');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracoes');
    }
}
