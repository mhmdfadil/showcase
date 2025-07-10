<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GambarKarya extends Model
{
    use HasFactory;

    protected $fillable = ['karya_id', 'nama_gambar'];

    public function karya()
    {
        return $this->belongsTo(Karya::class);
    }
}