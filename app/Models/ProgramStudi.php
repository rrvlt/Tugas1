<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProgramStudi extends Model {
    protected $table='program_studis';
    protected $fillable=['kode', 'nama', 'aktif'];
    public function mahasiswas() { return $this->hasMany(Mahasiswa::class); }
    public function dosens() { return $this->hasMany(Dosen::class); }
    protected $casts=['aktif'=>'boolean'];
}
