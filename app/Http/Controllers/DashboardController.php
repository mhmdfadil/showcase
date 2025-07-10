<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karya;
use App\Models\Kategori;
use App\Models\Tag;
use App\Models\Rating;
use App\Models\Komentar;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function indexAdmin()
    {
        $user = auth()->user();
        
        // User Statistics
        $totalUsers = User::count();
        $activeUsers = User::where('status_login', 1)->count();
        $newUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        
        // Karya Statistics
        $totalKarya = Karya::count();
        $publishedKarya = Karya::where('status', 'DISETUJUI')->count();
        $pendingKarya = Karya::where('status', 'MENUNGGU')->count();
        $rejectedKarya = Karya::where('status', 'DITOLAK')->count();
        
        // Category Statistics
        $categories = Kategori::withCount('karyas')->orderBy('karyas_count', 'desc')->limit(5)->get();
        
        // Tag Statistics
        $popularTags = Tag::withCount('karyas')->orderBy('karyas_count', 'desc')->limit(5)->get();
        
        // Rating Statistics
        $averageRating = number_format(Karya::avg('avg_rating') ?? 0, 1);
        $topRatedKarya = Karya::orderBy('avg_rating', 'desc')->limit(5)->get();
        
        // Recent Comments
        $recentComments = Komentar::with(['user', 'karya'])
            ->orderBy('waktu_komen', 'desc')
            ->limit(5)
            ->get();
            
        // Recent Karya
        $recentKarya = Karya::with(['user', 'kategori'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // User Activity
        $userActivity = $user->sessions()
            ->orderBy('last_activity', 'desc')
            ->limit(5)
            ->get();

        $jumlahKategori = Kategori::count();

        return view('pages.admin.dashboard', compact(
            'user',
            'totalUsers',
            'activeUsers',
            'newUsers',
            'jumlahKategori',
            'totalKarya',
            'publishedKarya',
            'pendingKarya',
            'rejectedKarya',
            'categories',
            'popularTags',
            'averageRating',
            'topRatedKarya',
            'recentComments',
            'recentKarya',
            'userActivity'
        ));
    }

      public function indexMhs()
    {
        $user = auth()->user();
        
        // User Statistics
        $totalUsers = User::count();
        $activeUsers = User::where('status_login', 1)->where('id', $user->id)->count();
        $newUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        
        // Karya Statistics
        $totalKarya = Karya::where('user_id', $user->id)->count();
        $publishedKarya = Karya::where('status', 'DISETUJUI')->where('user_id', $user->id)->count();
        $pendingKarya = Karya::where('status', 'MENUNGGU')->where('user_id', $user->id)->count();
        $rejectedKarya = Karya::where('status', 'DITOLAK')->where('user_id', $user->id)->count();
        
        // Category Statistics
        $categories = Kategori::withCount([
            'karyas' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }
        ])
        ->orderBy('karyas_count', 'desc')
        ->limit(5)
        ->get();

        // Tag Statistics
        $popularTags = Tag::withCount('karyas')->orderBy('karyas_count', 'desc')->limit(5)->get();
        
        // Rating Statistics
        $averageRating = number_format(
            Karya::where('user_id', $user->id)->avg('avg_rating') ?? 0,
            1
        );

        $topRatedKarya = Karya::orderBy('avg_rating', 'desc')->where('user_id', $user->id)->limit(5)->get();
        
        // Recent Comments
        $recentComments = Komentar::with(['user', 'karya'])
            ->orderBy('waktu_komen', 'desc')
            ->where('user_id', $user->id)
            ->limit(5)
            ->get();
            
        // Recent Karya
        $recentKarya = Karya::with(['user', 'kategori'])
            ->orderBy('created_at', 'desc')
            ->where('user_id', $user->id)
            ->limit(5)
            ->get();
            
        // User Activity
        $userActivity = $user->sessions()
            ->orderBy('last_activity', 'desc')
            ->limit(5)
            ->get();

        $jumlahKategori = Kategori::count();

        return view('pages.mahasiswa.dashboard', compact(
            'user',
            'totalUsers',
            'activeUsers',
            'newUsers',
            'jumlahKategori',
            'totalKarya',
            'publishedKarya',
            'pendingKarya',
            'rejectedKarya',
            'categories',
            'popularTags',
            'averageRating',
            'topRatedKarya',
            'recentComments',
            'recentKarya',
            'userActivity'
        ));
    }
}