<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->nullable()->constrained('mahasiswas')->nullOnDelete();
            $table->string('nama');
            $table->string('email');
            $table->string('whatsapp')->nullable();
            $table->string('perihal');
            $table->text('pesan');
            $table->enum('status', ['baru', 'dibaca', 'dijawab'])->default('baru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
