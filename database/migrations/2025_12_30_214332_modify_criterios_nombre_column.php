<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('criterios', function (Blueprint $table) {
            $table->string('nombre', 500)->change();
        });
    }

    public function down(): void
    {
        Schema::table('criterios', function (Blueprint $table) {
            $table->string('nombre', 255)->change();
        });
    }
};
