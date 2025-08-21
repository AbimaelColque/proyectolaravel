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
        Schema::create('detalle_ventas', function (Blueprint $table) {
        $table->engine = "InnoDB";
        $table->bigIncrements('id');
        $table->integer('cantidad');
        $table->decimal('precio', 10, 2);
        $table->decimal('total', 10, 2);
        $table->bigInteger('venta_id')->unsigned();
        $table->foreign('venta_id')->references('id')->on('ventas')->onDelete('cascade');
        $table->bigInteger('producto_id')->unsigned();
        $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
        $table->timestamps();
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
