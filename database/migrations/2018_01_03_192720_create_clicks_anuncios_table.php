<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClicksAnunciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clicks_anuncios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('nome_usuario');
            $table->string('nome_tipo_anuncio');
            $table->string('nome_anuncio');
            $table->string('nome_estabelecimento');
            $table->string('ip_click');
            $table->double('valor_click', 10, 2);
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
        Schema::dropIfExists('clicks_anuncios');
    }
}
