<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_groups', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('site_group_site', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['site_group_id', 'site_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_group_site');
        Schema::dropIfExists('site_groups');
    }
};
