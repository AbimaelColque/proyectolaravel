<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
        $table->engine = "InnoDB";
        $table->bigIncrements('id');
        $table->date('fecha');
        $table->decimal('total', 10, 2);
        $table->timestamps();
        $table->bigInteger('cliente_id')->unsigned();
        $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
