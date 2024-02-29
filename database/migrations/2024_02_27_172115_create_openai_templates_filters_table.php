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
        Schema::create('openai_templates_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->references('id')->on('openai')->cascadeOnDelete();
            $table->foreignId('filter_id')->references('id')->on('openai_filters')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('openai_templates_filters');
    }
};
