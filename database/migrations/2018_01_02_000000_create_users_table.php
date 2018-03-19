<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 150)->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('cidade')->nullable();
            $table->integer('uf')->unsigned()->nullable();
            $table->foreign('uf')->references('id')->on('lista_ufs');
            $table->string('cep')->nullable();
            $table->enum('conta_vinculada', [0, 1, 2])->default(0)->comment('0 = NAO / 1 = FACEBOOK / 2 = GOOGLE');
            $table->double('credito_disponivel', 10, 2)->default(0);
            $table->boolean('is_admin')->default(0);
            $table->string('email_token')->unique()->nullable();
            $table->boolean('email_verificado')->default(0);
            $table->dateTime('data_ultimo_acesso')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
