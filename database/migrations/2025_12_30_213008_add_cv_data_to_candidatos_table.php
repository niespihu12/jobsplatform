<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->text('cv_texto')->nullable();
            $table->json('experiencia')->nullable();
            $table->json('educacion')->nullable();
            $table->json('habilidades')->nullable();
            $table->json('idiomas')->nullable();
            $table->json('certificaciones')->nullable();
            $table->integer('score')->nullable();
            $table->json('evaluacion_ia')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->dropColumn(['cv_texto', 'experiencia', 'educacion', 'habilidades', 'idiomas', 'certificaciones', 'score', 'evaluacion_ia']);
        });
    }
};
