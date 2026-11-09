<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('dosens',function(Blueprint $t) {
            $t->id(); $t->foreignId('program_studi_id')->constrained('program_studis')->restrictOnDelete();
            $t->string('nidn',20)->unique(); $t->string('nama',100); $t->string('email',150)->unique(); $t->timestamps();
        });
        Schema::create('profil_dosens',function(Blueprint $t) {
            $t->id(); $t->foreignId('dosen_id')->unique()->constrained('dosens')->cascadeOnDelete();
            $t->string('telepon',20)->nullable(); $t->text('alamat')->nullable(); $t->timestamps();
        });
        Schema::create('keahlians',function(Blueprint $t) {
            $t->id(); $t->string('kode',20)->unique(); $t->string('nama',100)->unique(); $t->timestamps();
        });
        Schema::create('dosen_keahlian',function(Blueprint $t) {
            $t->id(); $t->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();
            $t->foreignId('keahlian_id')->constrained('keahlians')->restrictOnDelete();
            $t->enum('level',['pemula','menengah','ahli']); $t->unsignedSmallInteger('tahun_mulai'); $t->timestamps();
            $t->unique(['dosen_id','keahlian_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('dosen_keahlian'); Schema::dropIfExists('profil_dosens');
        Schema::dropIfExists('dosens'); Schema::dropIfExists('keahlians');
    }
};
