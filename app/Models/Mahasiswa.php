<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Mahasiswa extends Model
{
    protected $table = 'mahasiswas';
    protected $fillable = ['nim', 'nama', 'email', 'program_studi_id', 'angkatan', 'status'];
    protected $casts = ['angkatan' => 'integer'];
    public function programStudi() { return $this->belongsTo(ProgramStudi::class); }
    public function profil() { return $this->hasOne(ProfilMahasiswa::class); }
    public function mataKuliahs() { return $this->belongsToMany(MataKuliah::class,'krs')->withPivot(['id','tahun_akademik','semester','status'])->withTimestamps(); }
    public function krsPeriode(string $tahun, string $semester) { return $this->mataKuliahs()->wherePivot('tahun_akademik',$tahun)->wherePivot('semester',$semester); }
}
