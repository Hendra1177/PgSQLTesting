<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class daftar_soal extends Model
{
    use HasFactory;
    protected $table = 'daftar_soal';
    protected $fillable=[
        'id',
        'nomor',
        'soal',
        'jenis',
        'dbname',
        'jawaban',
        'testcode',
    ];
}
