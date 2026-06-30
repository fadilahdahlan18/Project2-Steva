<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatih_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nama_kelas');
            $table->enum('kategori_kelas', ['pemula', 'madya', 'ahli'])->nullable();
            $table->string('jenis_kelas')->nullable(); // e.g. 'rampak' or 'reguler'
            $table->string('hari');
            $table->string('jam');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal');
    }
};
