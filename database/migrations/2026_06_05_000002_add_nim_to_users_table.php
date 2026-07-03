<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim')->nullable()->after('id');
        });

        foreach (DB::table('users')->orderBy('id')->get() as $user) {
            if (empty($user->nim)) {
                DB::table('users')->where('id', $user->id)->update([
                    'nim' => 'LEGACY'.$user->id,
                ]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('nim')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nim');
        });
    }
};
