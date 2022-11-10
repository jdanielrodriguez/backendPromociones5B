<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('read')->nullable()->default(1);
            $table->integer('write')->nullable()->default(1);
            $table->integer('update')->nullable()->default(1);
            $table->integer('admin')->nullable()->default(1);

            $table->integer('rol')->nullable()->default(null)->unsigned();
            $table->foreign('rol')->references('id')->on('roles')->onDelete('cascade');

            $table->integer('user')->nullable()->default(null)->unsigned();
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('permissions');
    }
}
