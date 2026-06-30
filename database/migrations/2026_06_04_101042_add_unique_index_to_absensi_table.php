<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Clean up duplicate attendance records keeping the latest one
        $duplicates = DB::table('absensi')
            ->select('user_id', 'jadwal_id', 'tanggal', DB::raw('MAX(id) as max_id'))
            ->groupBy('user_id', 'jadwal_id', 'tanggal')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $dup) {
            DB::table('absensi')
                ->where('user_id', $dup->user_id)
                ->where('jadwal_id', $dup->jadwal_id)
                ->where('tanggal', $dup->tanggal)
                ->where('id', '<', $dup->max_id)
                ->delete();
        }

        Schema::table('absensi', function (Blueprint $table) {
            $table->unique(['user_id', 'jadwal_id', 'tanggal'], 'absensi_user_jadwal_tanggal_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropUnique('absensi_user_jadwal_tanggal_unique');
        });
    }
};
