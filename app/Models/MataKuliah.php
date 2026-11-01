<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MataKuliah extends Model
{
    protected $table = 'mata_kuliahs';
    protected $fillable = ['kode', 'nama', 'sks', 'semester', 'deskripsi'];
    protected $casts = ['sks' => 'integer', 'semester' => 'integer'];
}
