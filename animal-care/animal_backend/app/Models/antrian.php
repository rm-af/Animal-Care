<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'antrian';
    protected $primaryKey = 'antrianId';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'userId',
        'hewanId',
        'layananId',
        'nama_hewan',
        'keluhan',
        'no_hp',
        'status',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    // Relasi ke Hewan
    public function hewan()
    {
        return $this->belongsTo(Hewan::class, 'hewanId');
    }

    // Relasi ke Layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layananId');
    }
}
