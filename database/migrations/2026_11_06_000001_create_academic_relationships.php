<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void {
        Schema::create('program_studis', function(Blueprint $t) {
            $t->id(); $t->string('kode',20)->unique(); $t->string('nama',100)->unique();
            $t->boolean('aktif')->default(true); $t->timestamps();
        });
        Schema::table('mahasiswas', function(Blueprint $t) {
            $t->foreignId('program_studi_id')->nullable()->constrained('program_studis')->restrictOnDelete();
        });
        // Backfill before removing the denormalized name. No model dependency in migration.
        DB::table('mahasiswas')->orderBy('id')->chunkById(100, function($rows) {
            foreach($rows as $row) {
                $name=trim($row->program_studi) ?: 'Belum ditentukan';
                $id=DB::table('program_studis')->where('nama',$name)->value('id');
                if (!$id) {
                    $id=DB::table('program_studis')->insertGetId(['kode'=>'LEGACY'.str_pad((string)$row->id,10,'0',STR_PAD_LEFT),
                        'nama'=>$name,'aktif'=>true,'created_at'=>now(),'updated_at'=>now()]);
                }
                DB::table('mahasiswas')->where('id',$row->id)->update(['program_studi_id'=>$id]);
            }
        });
        Schema::table('mahasiswas', function(Blueprint $t) {
            $t->unsignedBigInteger('program_studi_id')->nullable(false)->change();
            $t->dropColumn('program_studi');
        });
        Schema::create('profil_mahasiswas', function(Blueprint $t) {
            $t->id(); $t->foreignId('mahasiswa_id')->unique()->constrained('mahasiswas')->cascadeOnDelete();
            $t->string('tempat_lahir',100)->nullable(); $t->date('tanggal_lahir')->nullable();
            $t->string('telepon',20)->nullable(); $t->text('alamat')->nullable(); $t->timestamps();
        });
        Schema::table('mata_kuliahs', fn(Blueprint $t)=>$t->boolean('aktif')->default(true));
        Schema::create('krs', function(Blueprint $t) {
            $t->id(); $t->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $t->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->restrictOnDelete();
            $t->string('tahun_akademik',9); $t->enum('semester',['ganjil','genap']);
            $t->enum('status',['draft','disetujui'])->default('draft'); $t->timestamps();
            $t->unique(['mahasiswa_id','mata_kuliah_id','tahun_akademik','semester'],'krs_unique');
        });
    }
    public function down(): void {
        Schema::dropIfExists('krs'); Schema::dropIfExists('profil_mahasiswas');
        Schema::table('mata_kuliahs',fn(Blueprint $t)=>$t->dropColumn('aktif'));
        Schema::table('mahasiswas',fn(Blueprint $t)=>$t->string('program_studi',100)->default(''));
        DB::table('mahasiswas')->orderBy('id')->chunkById(100,function($rows) {
            foreach($rows as $r) DB::table('mahasiswas')->where('id',$r->id)->update([
                'program_studi'=>DB::table('program_studis')->where('id',$r->program_studi_id)->value('nama') ?? 'Belum ditentukan']);
        });
        Schema::table('mahasiswas',fn(Blueprint $t)=>$t->dropConstrainedForeignId('program_studi_id'));
        Schema::dropIfExists('program_studis');
    }
};
