<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class submission extends Model
{
    use HasFactory;
    protected $table = 'submission';
    protected $fillable=[
        'id',
        'mahasiswa_id',
        'soal_id',
        'status',
        'solution',
    ];
    
    public function soal()
    {
        return $this->belongsTo(soal::class, 'soal_id', 'id');
    }
    
}
