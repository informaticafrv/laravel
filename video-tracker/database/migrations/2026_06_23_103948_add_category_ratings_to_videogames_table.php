<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videogames', function (Blueprint $table) {
            $table->decimal('nota_grafica',    3, 1)->nullable()->after('puntuacion_personal');
            $table->decimal('nota_historia',   3, 1)->nullable()->after('nota_grafica');
            $table->decimal('nota_jugabilidad',3, 1)->nullable()->after('nota_historia');
            $table->decimal('nota_duracion',   3, 1)->nullable()->after('nota_jugabilidad');
        });
    }

    public function down(): void
    {
        Schema::table('videogames', function (Blueprint $table) {
            $table->dropColumn(['nota_grafica', 'nota_historia', 'nota_jugabilidad', 'nota_duracion']);
        });
    }
};
