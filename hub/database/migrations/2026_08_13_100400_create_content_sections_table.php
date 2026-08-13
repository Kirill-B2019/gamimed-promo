<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('site_group_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('scope_key', 64)->default('global');
            $table->string('section_key');
            $table->string('locale', 16);
            $table->json('payload')->nullable();
            $table->string('status', 32)->default('draft');
            $table->timestamps();

            $table->unique(['scope_key', 'section_key', 'locale'], 'content_sections_scope_unique');
            $table->index(['section_key', 'locale', 'status']);
            $table->index(['site_id', 'section_key', 'locale']);
            $table->index(['site_group_id', 'section_key', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_sections');
    }
};
