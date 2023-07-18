<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class soal extends Model
{
    use HasFactory;
    protected $table = 'soal';
    protected $fillable=[
        'id',
        'no',
        'judul',
        'soal',
        'jenis',
        'deskripsi',
        'jawaban',
        'code',
    ];

    public function submission()
    {
        return $this->hasMany(submission::class);
    }
}
