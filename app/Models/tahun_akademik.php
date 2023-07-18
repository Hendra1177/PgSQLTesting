<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tahun_akademik extends Model
{
    use HasFactory;
    protected $table = 'tahun_akademik';
    protected $fillable=[
        'id',
        'nama',
        'semester',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status',
    ];
}
