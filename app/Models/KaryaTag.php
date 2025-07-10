<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class KaryaTag extends Pivot
{
    protected $table = 'karya_tags';
    
    // Jika perlu fillable
    protected $fillable = [
        'karya_id',
        'tag_id',
        // tambahan kolom lain jika ada
    ];

    public function karya()
    {
        return $this->belongsTo(Karya::class);
    }
    
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}