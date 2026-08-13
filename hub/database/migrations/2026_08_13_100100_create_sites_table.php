<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('domain')->nullable();
            $table->json('locales')->nullable();
            $table->string('default_locale', 16)->default('en');
            $table->string('status', 32)->default('active');
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index('status', 'sites_status_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
