<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class soal_latihan extends Model
{
    use HasFactory;
    protected $table = 'soal_latihan';
    protected $fillable=[
        'id',
        'latihan_id',
        'soal_id',
        'no',
        'isRemoved',
    ];
    public function exercise()
    {
        return $this->belongsTo(latihan::class, 'latihan_id');
    }

    public function question()
    {
        return $this->belongsTo(soal::class, 'soal_id');
    }
}
