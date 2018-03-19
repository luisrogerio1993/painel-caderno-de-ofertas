<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstabelecimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estabelecimentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 150)->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('nome')->unique();
            $table->integer('categoria_estabelecimento')->unsigned();
            $table->foreign('categoria_estabelecimento')->references('id')->on('categorias_estabelecimentos');
            $table->string('descricao_estabelecimento')->nullable();
            $table->string('rua');
            $table->integer('numero');
            $table->string('complemento')->nullable();
            $table->string('bairro');
            $table->string('cpf')->unique()->nullable();
            $table->string('cnpj')->unique()->nullable();
            $table->string('ddd_telefone_fixo')->nullable();
            $table->string('telefone_fixo')->nullable();
            $table->string('ddd_telefone_celular')->nullable();
            $table->string('telefone_celular')->nullable();
            $table->boolean('exibir_telefone_fixo_clientes')->default(1);
            $table->boolean('exibir_telefone_celular_clientes')->default(1);
            $table->boolean('telefone_celular_is_whatsapp')->default(0);
            $table->boolean('estabelecimento_verificado')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estabelecimentos');
    }
}
