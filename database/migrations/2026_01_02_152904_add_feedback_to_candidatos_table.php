<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->integer('feedback_score')->nullable();
            $table->text('feedback_comentario')->nullable();
            $table->boolean('contratado')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->dropColumn(['feedback_score', 'feedback_comentario', 'contratado']);
        });
    }
};
