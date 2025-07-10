<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kategori'];

    /**
     * Get all the karyas for the kategori.
     */
    public function karyas()
    {
        return $this->hasMany(Karya::class);
    }
}