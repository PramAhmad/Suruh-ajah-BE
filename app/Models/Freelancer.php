<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freelancer extends Model
{
    use HasFactory;
    protected $table = 'freelancers';
    protected $fillable = [
        'user_id',
        'ktp',
        'foto_ktp',
    ];
}
