<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MataKuliah extends Model
{
    protected $table = 'mata_kuliahs';
    protected $fillable = ['kode', 'nama', 'sks', 'semester', 'deskripsi', 'aktif'];
    protected $casts = ['sks' => 'integer', 'semester' => 'integer'];
    public function mahasiswas() { return $this->belongsToMany(Mahasiswa::class,'krs')->withPivot(['tahun_akademik','semester','status'])->withTimestamps(); }
}
