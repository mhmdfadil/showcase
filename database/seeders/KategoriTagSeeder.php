<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class KategoriTagSeeder extends Seeder
{
    public function run()
    {
        // Data kategori contoh
        $kategoris = ['Teknologi', 'Kesehatan', 'Pendidikan', 'Bisnis'];
        
        foreach ($kategoris as $kategori) {
            Kategori::create(['nama_kategori' => $kategori]);
        }

        // Data tag contoh
        $tags = ['PHP', 'Laravel', 'JavaScript', 'HTML', 'CSS'];
        
        foreach ($tags as $tag) {
            Tag::create(['nama_tag' => $tag]);
        }
    }
}