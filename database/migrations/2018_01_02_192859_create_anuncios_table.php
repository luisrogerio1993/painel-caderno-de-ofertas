<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnunciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anuncios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 150)->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('estabelecimento_id')->unsigned();
            $table->foreign('estabelecimento_id')->references('id')->on('estabelecimentos')->onDelete('cascade');
            $table->string('nome');
            $table->integer('categoria_anuncio_id')->unsigned();
            $table->foreign('categoria_anuncio_id')->references('id')->on('categorias_estabelecimentos');
            $table->text('descricao');
            $table->double('valor_atual', 10, 2);
            $table->double('valor_original', 10, 2);
            $table->dateTime('anuncio_valido_ate');
            $table->integer('tipo_anuncio')->unsigned();
            $table->foreign('tipo_anuncio')->references('id')->on('tipos_anuncio')->onDelete('cascade');
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
        Schema::dropIfExists('anuncios');
    }
}