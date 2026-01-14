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
        Schema::create('emissions', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->integer('year');
            $table->decimal('energy', 12, 2);
            $table->decimal('co2', 12, 2);
            $table->string('sector');
            $table->timestamps();
            $table->unique(['company', 'year', "sector"]);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emissions');
    }
};
