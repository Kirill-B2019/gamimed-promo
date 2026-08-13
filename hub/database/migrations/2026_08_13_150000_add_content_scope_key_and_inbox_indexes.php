<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('content_sections', 'scope_key')) {
            Schema::table('content_sections', function (Blueprint $table) {
                $table->string('scope_key', 64)->default('global')->after('site_group_id');
            });
        }

        if (Schema::hasColumn('content_sections', 'scope_key')) {
            foreach (DB::table('content_sections')->orderBy('id')->get() as $row) {
                $scopeKey = $row->site_id
                    ? 'site:'.$row->site_id
                    : ($row->site_group_id ? 'group:'.$row->site_group_id : 'global');

                DB::table('content_sections')->where('id', $row->id)->update([
                    'scope_key' => $scopeKey,
                ]);
            }
        }

        if (! Schema::hasIndex('content_sections', 'content_sections_scope_unique')) {
            Schema::table('content_sections', function (Blueprint $table) {
                $table->unique(['scope_key', 'section_key', 'locale'], 'content_sections_scope_unique');
            });
        }

        if (! Schema::hasIndex('contact_messages', 'contact_messages_inbox_index')) {
            Schema::table('contact_messages', function (Blueprint $table) {
                $table->index(['site_id', 'status', 'created_at'], 'contact_messages_inbox_index');
            });
        }

        if (! Schema::hasIndex('sites', 'sites_status_index')) {
            Schema::table('sites', function (Blueprint $table) {
                $table->index('status', 'sites_status_index');
            });
        }
    }

    public function down(): void
    {
        // Canonical schema lives on the create_* migrations; this file is idempotent for existing databases.
    }
};
