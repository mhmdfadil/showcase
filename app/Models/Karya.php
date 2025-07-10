<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karya extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'slug',
        'jenis_karya',
        'tahun',
        'user_id',
        'status',
        'video_karya',
        'dokumen_karya',
        'tanggal_pengajuan',
        'tanggal_publish',
        'keterangan_revisi',
        'views',
        'avg_rating',
        'kategori_id'
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_publish' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function gambarKaryas()
    {
        return $this->hasMany(GambarKarya::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'karya_tags');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // app/Models/Karya.php
    public function komentars()
    {
        return $this->hasMany(Komentar::class);
    }
}