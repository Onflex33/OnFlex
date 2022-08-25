<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_activo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pago');
            $table->date('fecha_act');
            $table->integer('dias_act');
            $table->foreign('id_pago')->references('id')->on('pago_manual')->onDelete('cascade');
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
        Schema::table('pago_activo', function(Blueprint $table){
            $table->dropForeign(['id_pago']);
        });
        Schema::dropIfExists('pago_activo');
    }
};