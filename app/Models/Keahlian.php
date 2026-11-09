<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Keahlian extends Model {
    protected $table='keahlians';
    protected $fillable=['kode', 'nama'];
    public function dosens() { return $this->belongsToMany(Dosen::class,'dosen_keahlian')->withPivot(['level','tahun_mulai'])->withTimestamps(); }
}
