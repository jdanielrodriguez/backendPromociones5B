<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorreosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('correos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('asunto')->nullable()->default(null);
            $table->text('adjunto')->nullable()->default(null);
            $table->text('correo')->nullable()->default(null);
            $table->string('recibido')->nullable()->default(null);
            $table->timestamp('fecha')->useCurrent();
            $table->integer('whatsapp')->nullable()->default(0);
            $table->integer('estado')->nullable()->default(0);

            $table->integer('player')->nullable()->default(null)->unsigned();
            $table->foreign('player')->references('id')->on('players')->onDelete('cascade');

            $table->integer('move')->nullable()->default(null)->unsigned();
            $table->foreign('move')->references('id')->on('moves')->onDelete('cascade');
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
        Schema::dropIfExists('correos');
    }
}
