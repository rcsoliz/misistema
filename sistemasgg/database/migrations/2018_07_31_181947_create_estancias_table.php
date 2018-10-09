<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstanciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estancias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 60);
            $table->string('ubicacion', 100);
            $table->string('tamanio', 100);
            $table->string('distancia', 20);
            $table->string('razonsocial', 100);
            $table->string('nit', 15);
            $table->string('rua', 30);
            $table->string('observacion', 100);
            $table->integer('idtproveedor')->unsigned();
            $table->integer('idzona');
            $table->integer('iduser')->unsigned();
            $table->integer('idganadero');
            $table->boolean('condicion')->default(1);
            $table->timestamps();
            $table->foreign('idtproveedor')->references('id')->on('parametros');
            $table->foreign('iduser')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estancias');
    }
}
