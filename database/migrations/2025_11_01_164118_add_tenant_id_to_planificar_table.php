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
        Schema::table('planificar', function (Blueprint $table) {
            // Agregamos la columna tenant_id
            $table->unsignedBigInteger('tenant_id')->after('ruta_id')->nullable();

            // Opcional: relación con la tabla tenants
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planificar', function (Blueprint $table) {
            // Para revertir la migración
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};
