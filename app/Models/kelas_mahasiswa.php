<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kelas_mahasiswa extends Model
{
    use HasFactory;
    protected $table = 'kelas_mahasiswa';
    protected $fillable=[
        'id',
        'kelas_id',
        'mahasiswa_id',
    ];

    public function class()
    {
        return $this->belongsTo(kelas::class, 'kelas_id', 'id');
    }
}
