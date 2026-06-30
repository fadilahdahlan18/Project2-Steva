<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwal')->onDelete('cascade');
            $table->enum('kategori_kelas', ['pemula', 'madya', 'ahli'])->nullable();
            $table->string('jenis_kelas')->nullable(); // e.g. 'rampak' or 'reguler'
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'alpha'])->default('alpha');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi');
    }
};
