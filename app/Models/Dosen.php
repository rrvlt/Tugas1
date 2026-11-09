<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Dosen extends Model {
    protected $table='dosens';
    protected $fillable=['nidn', 'nama', 'email', 'program_studi_id'];
    public function programStudi() { return $this->belongsTo(ProgramStudi::class); }
    public function profil() { return $this->hasOne(ProfilDosen::class); }
    public function keahlians() { return $this->belongsToMany(Keahlian::class,'dosen_keahlian')->withPivot(['level','tahun_mulai'])->withTimestamps(); }
}
