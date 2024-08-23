<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriJasa extends Model
{
    use HasFactory;
    protected $table = 'kategori_jasas';
    protected $fillable = [
        'nama_kategori',
    ];
}
