<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 16)->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('messenger')->nullable();
            $table->text('message')->nullable();
            $table->json('meta')->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->string('status', 32)->default('new');
            $table->timestamps();

            $table->index(['site_id', 'created_at']);
            $table->index(['site_id', 'status', 'created_at'], 'contact_messages_inbox_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
