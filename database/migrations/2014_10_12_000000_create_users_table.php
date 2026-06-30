<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'pelatih', 'murid'])->default('murid');
            $table->string('no_hp')->nullable();
            $table->string('foto')->nullable();
            $table->string('kode_pelatih')->nullable()->unique();
            $table->enum('kategori_kelas', ['pemula', 'madya', 'ahli'])->nullable();
            $table->string('jenis_kelas')->nullable(); // Can store multiple values like 'rampak,reguler'
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
