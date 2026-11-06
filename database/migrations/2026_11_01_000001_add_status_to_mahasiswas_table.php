<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Data sebelum tugas 4 diberi status aktif; dapat diperbarui melalui Edit.
            $table->string('status', 10)->default('aktif')->index();
        });
    }
    public function down(): void {
        Schema::table('mahasiswas', function (Blueprint $table) { $table->dropColumn('status'); });
    }
};
