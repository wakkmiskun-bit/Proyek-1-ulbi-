<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('task_reminders');
        Schema::dropIfExists('tasks');

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['todo', 'doing', 'review', 'done'])->default('todo');
            $table->date('deadline')->nullable();
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->json('checklist')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('task_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->unsignedTinyInteger('days_before');
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->unique(['task_id', 'days_before']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_reminders');
        Schema::dropIfExists('tasks');
    }
};
