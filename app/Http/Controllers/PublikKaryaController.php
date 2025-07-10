<?php

namespace App\Http\Controllers;

use App\Models\Karya;
use App\Models\Rating;
use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\KomentarBalas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublikKaryaController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount(['karyas' => function($query) {
            $query->where('status', 'DISETUJUI')
                ->where('tanggal_publish', '<=', now('Asia/Jakarta'));
        }])
        ->orderBy('nama_kategori')
        ->get()
        ->filter(function($kategori) {
            return $kategori->karyas_count > 0;
        });

        return view('pages.admin.all-karya.index', compact('kategoris'));
    }

    public function draft()
    {
        $perPage = 10;
        $page = request()->get('page', 1);
        
        $kategoris = Kategori::withCount(['karyas' => function($query) {
            $query->where('status', 'DISETUJUI')
                ->where('tanggal_publish', '>', now('Asia/Jakarta'));
        }])
        ->orderBy('nama_kategori')
        ->get()
        ->filter(function($kategori) {
            return $kategori->karyas_count > 0;
        });
        
        // Manual pagination
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $kategoris->forPage($page, $perPage),
            $kategoris->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('pages.admin.all-karya.draft', ['kategoris' => $paginated]);
    }

    public function publish()
    {
        $perPage = 10;
        $page = request()->get('page', 1);
        
        $kategoris = Kategori::withCount(['karyas' => function($query) {
            $query->where('status', 'DISETUJUI')
                ->where('tanggal_publish', '<=', now('Asia/Jakarta'));
        }])
        ->orderBy('nama_kategori')
        ->get()
        ->filter(function($kategori) {
            return $kategori->karyas_count > 0;
        });
        
        // Manual pagination
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $kategoris->forPage($page, $perPage),
            $kategoris->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('pages.admin.all-karya.publish', ['kategoris' => $paginated]);
    }

    public function byKategori(Kategori $kategori, Request $request)
    {
        $years = Karya::where('kategori_id', $kategori->id)
            ->where('status', 'DISETUJUI')
            ->selectRaw('EXTRACT(YEAR FROM tanggal_publish) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $query = Karya::where('kategori_id', $kategori->id)
            ->where('status', 'DISETUJUI');

        // Filter by jenis_karya if provided
        if ($request->has('jenis_karya')) {
            $query->where('jenis_karya', $request->jenis_karya);
        }

        // Filter by year if provided
        if ($request->has('year')) {
            $query->whereYear('tanggal_publish', $request->year);
        }

        $karyas = $query->orderBy('tanggal_publish', 'desc')
            ->paginate(12);

        return view('pages.admin.all-karya.by-kategori', compact('kategori', 'years', 'karyas'));
    }

    public function byKategoriAndYear(Kategori $kategori, $tahun, Request $request)
    {
        $years = Karya::where('kategori_id', $kategori->id)
            ->where('status', 'DISETUJUI')
            ->selectRaw('EXTRACT(YEAR FROM tanggal_publish) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $query = Karya::where('kategori_id', $kategori->id)
            ->where('status', 'DISETUJUI')
            ->whereRaw('EXTRACT(YEAR FROM tanggal_publish) = ?', [$tahun]);

        // Filter by jenis_karya if provided
        if ($request->has('jenis_karya')) {
            $query->where('jenis_karya', $request->jenis_karya);
        }

        $karyas = $query->orderBy('tanggal_publish', 'desc')
            ->paginate(12);

        return view('pages.admin.all-karya.by-kategori-year', compact('kategori', 'tahun', 'years', 'karyas'));
    }

    public function show(Karya $karya)
    {
        if ($karya->status !== 'DISETUJUI') {
            abort(404);
        }

        $relatedKaryas = Karya::where('kategori_id', $karya->kategori_id)
            ->where('id', '!=', $karya->id)
            ->where('status', 'DISETUJUI')
            ->orderBy('tanggal_publish', 'desc')
            ->limit(4)
            ->get();

        $karya->load(['user', 'kategori', 'tags', 'gambarKaryas', 'komentars.user']);
        
        // Check if user has already rated this karya
        $userRating = null;
        if (auth()->check()) {
            $userRating = Rating::where('karya_id', $karya->id)
                ->where('user_id', auth()->id())
                ->first();
        }
        
        // Increment views
        $karya->increment('views');

        return view('pages.admin.all-karya.show', compact('karya', 'relatedKaryas', 'userRating'));
    }

    public function rateKarya(Request $request, Karya $karya)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5'
        ]);

        // Check if user already rated this karya
        $existingRating = Rating::where('karya_id', $karya->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingRating) {
            return back()->with('error', 'Anda sudah memberikan rating untuk karya ini.');
        }

        // Create new rating
        $rating = Rating::create([
            'karya_id' => $karya->id,
            'user_id' => auth()->id(),
            'nilai_rate' => $request->rating
        ]);

        // Update average rating
        $avgRating = Rating::where('karya_id', $karya->id)->avg('nilai_rate');
        $karya->update(['avg_rating' => $avgRating]);

        return back()->with('success', 'Terima kasih telah memberikan rating!');
    }

    public function storeComment(Request $request, Karya $karya)
    {
        $request->validate([
            'komentar' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:komentars,id'
        ]);

        if ($request->parent_id) {
            // This is a reply
            $reply = KomentarBalas::create([
                'komentar_id' => $request->parent_id,
                'user_id' => auth()->id(),
                'komentar' => $request->komentar,
                'waktu_komen' => now()
            ]);

            return back()->with('success', 'Balasan berhasil dikirim!');
        }

        // This is a main comment
        $comment = Komentar::create([
            'karya_id' => $karya->id,
            'user_id' => auth()->id(),
            'komentar' => $request->komentar,
            'waktu_komen' => now()
        ]);

        return back()->with('success', 'Komentar berhasil dikirim!');
    }

    public function getReplies(Komentar $komentar, Request $request)
    {
        $offset = $request->query('offset', 5);
        
        $replies = $komentar->balasan()
            ->with('user')
            ->orderBy('waktu_komen', 'asc')
            ->skip($offset)
            ->take(5)
            ->get()
            ->map(function($reply) {
                return [
                    'id' => $reply->id,
                    'komentar' => $reply->komentar,
                    'waktu_komen' => $reply->waktu_komen->diffForHumans(),
                    'user' => [
                        'name' => $reply->user->name
                    ]
                ];
            });
        
        return response()->json([
            'success' => true,
            'replies' => $replies
        ]);
    }
}