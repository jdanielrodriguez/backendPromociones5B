<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('atm')->nullable()->default(null);
            $table->string('ubicacion')->nullable()->default(null);
            $table->string('direccion')->nullable()->default(null);
            $table->string('propietario')->nullable()->default(null);
            $table->string('tipo')->nullable()->default(null);
            $table->string('z_ubicacion')->nullable()->default(null);
            $table->string('t_ubicacion')->nullable()->default(null);
            $table->string('marca_atm')->nullable()->default(null);
            $table->string('modelo_atm')->nullable()->default(null);
            $table->tinyInteger('state')->nullable()->default(1);

            $table->integer('department')->nullable()->default(null)->unsigned();
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
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
        Schema::dropIfExists('atms');
    }
}
