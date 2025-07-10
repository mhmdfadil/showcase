<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'karya_id',
        'user_id',
        'nilai_rate'
    ];

    public function karya()
    {
        return $this->belongsTo(Karya::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($rating) {
            // Prevent duplicate ratings
            $existing = static::where('karya_id', $rating->karya_id)
                ->where('user_id', $rating->user_id)
                ->first();
            
            if ($existing) {
                return false;
            }
        });
    }
}