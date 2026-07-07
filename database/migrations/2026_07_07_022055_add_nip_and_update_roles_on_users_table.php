<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->nullable()->unique()->after('nisn');
        });

        // Update existing role values: 'student' → 'siswa'
        DB::table('users')->where('role', 'student')->update(['role' => 'siswa']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('siswa')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert role values: 'siswa' → 'student'
        DB::table('users')->where('role', 'siswa')->update(['role' => 'student']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student')->change();
            $table->dropColumn('nip');
        });
    }
};
