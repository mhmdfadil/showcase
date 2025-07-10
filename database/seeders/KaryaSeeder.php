<?php

namespace Database\Seeders;

use App\Models\Karya;
use App\Models\GambarKarya;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class KaryaSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $tags = Tag::take(3)->get();

        $karya = Karya::create([
            'judul' => 'Contoh Karya Video',
            'deskripsi' => 'Deskripsi contoh karya video',
            'slug' => 'contoh-karya-video',
            'jenis_karya' => 'video',
            'tahun' => 2023,
            'user_id' => $user->id,
            'status' => 'DISETUJUI',
            'video_karya' => 'contoh-video.mp4',
            'tanggal_pengajuan' => now()->subDays(10),
            'tanggal_publish' => now()->subDays(5),
            'kategori_id' => 1,
        ]);

        $karya->tags()->attach($tags);
    }
}