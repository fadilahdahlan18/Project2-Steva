<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->string('file_video')->nullable()->after('link_video');
        });
    }

    public function down()
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->dropColumn('file_video');
        });
    }
};
