<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('scan_kk')->nullable()->after('alamat');
            $table->string('scan_akta')->nullable()->after('scan_kk');
            $table->string('scan_ijazah')->nullable()->after('scan_akta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['scan_kk', 'scan_akta', 'scan_ijazah']);
        });
    }
};
