<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jasa extends Model
{
    use HasFactory;
    protected $table = 'jasas';
    protected $fillable = [
        'user_id',
        'nama_jasa',
        'kategori_jasa_id',
        'deskripsi',
        'kontak',
        'alamat',
        'harga',
        'waktu',
    ];
    public function kategori_jasa()
    {
        return $this->belongsTo(KategoriJasa::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
