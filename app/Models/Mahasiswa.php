<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Mahasiswa extends Model
{
    protected $table = 'mahasiswas';
    protected $fillable = ['nim', 'nama', 'email', 'program_studi', 'angkatan'];
    protected $casts = ['angkatan' => 'integer'];
}
