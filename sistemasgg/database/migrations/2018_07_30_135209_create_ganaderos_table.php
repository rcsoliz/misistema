<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGanaderosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ganaderos', function (Blueprint $table) {
    		$table->increments('id');
            $table->decimal('codigo', 10,0);
            $table->string('nombre',50);
            $table->string('apellido',60);
            $table->string('direccion', 80);
            $table->string('telefeno', 30);
            $table->string('celular', 30);
            $table->string('email', 50);
            $table->string('razonsocial', 80);
            $table->string('confactura', 15);
            $table->boolean('condicion')->default(1);
            $table->integer('iduser')->unsigned();
            $table->foreign('iduser')->references('id')->on('users');
            $table->timestamps();
            $table->string('ncompleto',110);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ganaderos');
    }
}
