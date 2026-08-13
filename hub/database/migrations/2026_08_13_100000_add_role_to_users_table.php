<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 32)->default('viewer')->after('password');
        });
    }

    public function down(): void
    {
        // Columns now live on create_users; keep rollback a no-op for migrate:fresh.
    }
};
