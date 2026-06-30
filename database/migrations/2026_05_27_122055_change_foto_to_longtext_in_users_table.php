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
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users MODIFY COLUMN foto LONGTEXT NULL');
    }

    public function down()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users MODIFY COLUMN foto VARCHAR(255) NULL');
    }
};
