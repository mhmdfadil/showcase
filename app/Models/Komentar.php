<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;

    protected $fillable = [
        'karya_id',
        'user_id',
        'komentar',
        'waktu_komen'
    ];

    // app/Models/Komentar.php
    protected $dates = ['waktu_komen'];

    // Or for Laravel 8+
    protected $casts = [
        'waktu_komen' => 'datetime'
    ];
    public function karya()
    {
        return $this->belongsTo(Karya::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function balasan()
    {
        return $this->hasMany(KomentarBalas::class);
    }
}