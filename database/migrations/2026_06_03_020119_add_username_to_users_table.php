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
        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->after('nama');
            });
        }

        // Populate existing users
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            if (empty($user->username)) {
                $baseUsername = strtolower(str_replace(' ', '', $user->nama));
                if (empty($baseUsername)) {
                    $baseUsername = 'user';
                }
                $username = $baseUsername;
                $count = 1;
                while (DB::table('users')->where('username', $username)->exists()) {
                    $username = $baseUsername . $count;
                    $count++;
                }
                DB::table('users')->where('id', $user->id)->update(['username' => $username]);
            }
        }

        // Change column to NOT NULL
        DB::statement("ALTER TABLE users MODIFY username VARCHAR(255) NOT NULL");

        // Check if unique index already exists, if not, add it
        $indexes = DB::select("SHOW INDEX FROM users WHERE Column_name = 'username' AND Non_unique = 0");
        if (count($indexes) === 0) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('username');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop unique constraint if exists
            $indexes = DB::select("SHOW INDEX FROM users WHERE Column_name = 'username' AND Non_unique = 0");
            if (count($indexes) > 0) {
                $table->dropUnique($indexes[0]->Key_name);
            }
            $table->dropColumn('username');
        });
    }
};
