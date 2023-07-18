<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kelas extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $fillable=[
        'id',
        'tahun_akademik_id',
        'dosen_id',
        'nama',
    ];

    public function teacher()
    {
        return $this->belongsTo(dosen::class, 'dosen_id', 'id');
    }

    public function year()
    {
        return $this->belongsTo(tahun_akademik::class, 'tahun_akademik_id', 'id');
    }
}
