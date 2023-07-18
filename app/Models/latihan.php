<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class latihan extends Model
{
    use HasFactory;
    protected $table = 'latihan';
    protected $fillable=[
        'id',
        'tahun_akademik_id',
        'nama',
        'deskripsi',
    ];
    public function year()
    {
        return $this->belongsTo(tahun_akademik::class, 'tahun_akademik_id', 'id');
    }
}
