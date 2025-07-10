<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomentarBalas extends Model
{
    use HasFactory;

    protected $fillable = [
        'komentar_id',
        'user_id',
        'komentar',
        'waktu_komen'
    ];
    // app/Models/KomentarBalas.php
    protected $dates = ['waktu_komen'];

    // Or for Laravel 8+
    protected $casts = [
        'waktu_komen' => 'datetime'
    ];

    public function komentar()
    {
        return $this->belongsTo(Komentar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}