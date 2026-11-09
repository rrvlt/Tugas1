<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProfilMahasiswa extends Model {
    protected $table='profil_mahasiswas';
    protected $fillable=['tempat_lahir', 'tanggal_lahir', 'telepon', 'alamat'];
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class); }
    protected $casts=['tanggal_lahir'=>'date'];
}
