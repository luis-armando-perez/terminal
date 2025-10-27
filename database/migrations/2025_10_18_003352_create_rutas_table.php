<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    /**
     * Run the migrations.
     */


    public function up(): void
    {

        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->string('origen');
            $table->string('destino');
            $table->string('trasporte');
            $table->string('tipo');
            $table->string('salida');
            $table->string('llegada');
            $table->decimal('precio', 8, 2); // 8 dígitos totales, 2 decimales
            $table->decimal('latitud_origen', 10, 8)->nullable();
            $table->decimal('longitud_origen', 11, 8)->nullable();
            $table->decimal('latitud_destino', 10, 8)->nullable();
            $table->decimal('longitud_destino', 11, 8)->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};
