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
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswas', 'points')) {
                $table->integer('points')->default(0)->after('semester');
            }
            if (!Schema::hasColumn('mahasiswas', 'level')) {
                $table->integer('level')->default(1)->after('points');
            }
        });

        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'tag')) {
                $table->string('tag')->nullable()->after('priority');
            }
            if (!Schema::hasColumn('tasks', 'attachments')) {
                $table->json('attachments')->nullable()->after('checklist');
            }
            if (!Schema::hasColumn('tasks', 'assigned_to')) {
                $table->string('assigned_to')->nullable()->after('attachments');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['points', 'level']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['tag', 'attachments', 'assigned_to']);
        });
    }
};
