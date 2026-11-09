<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProfilDosen extends Model {
    protected $table='profil_dosens';
    protected $fillable=['telepon', 'alamat'];
    public function dosen() { return $this->belongsTo(Dosen::class); }
}
