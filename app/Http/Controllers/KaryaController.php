<?php

namespace App\Http\Controllers;

use App\Models\Karya;
use App\Models\GambarKarya;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class KaryaController extends Controller
{
    public function create()
    {
        $users = User::all();
        return view('pages.admin.karya.create', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'video_karya' => [
                'nullable',
                File::types(['mp4', 'mov', 'avi', 'mkv'])
                    ->max(50 * 1024) // 50MB
            ],
            'dokumen_karya' => [
                'nullable',
                File::types(['pdf', 'doc', 'docx'])
                    ->max(3 * 1024) // 3MB
            ],
            'gambar_karya' => [
                'nullable',
                'array',
                'max:3'
            ],
            'gambar_karya.*' => [
                File::types(['jpg', 'jpeg', 'png', 'gif'])
                    ->max(2 * 1024) // 2MB per gambar
            ]
        ], [
            'gambar_karya.max' => 'Maksimal 3 gambar yang dapat diunggah',
            'video_karya.max' => 'Ukuran video maksimal 50MB',
            'dokumen_karya.max' => 'Ukuran dokumen maksimal 3MB'
        ]);

        // Validasi hanya satu jenis file yang diupload
        $fileTypes = 0;
        if ($request->hasFile('video_karya')) $fileTypes++;
        if ($request->hasFile('dokumen_karya')) $fileTypes++;
        if ($request->hasFile('gambar_karya')) $fileTypes += count($request->file('gambar_karya'));

        if ($fileTypes === 0) {
            return back()->withErrors(['file' => 'Harap unggah minimal satu file karya'])->withInput();
        }

        if ($fileTypes > 1 && (
            ($request->hasFile('video_karya') && $request->hasFile('dokumen_karya')) || 
            ($request->hasFile('video_karya') && $request->hasFile('gambar_karya')) || 
            ($request->hasFile('dokumen_karya') && $request->hasFile('gambar_karya')))
        ) {
            return back()->withErrors(['file' => 'Hanya boleh mengunggah satu jenis karya'])->withInput();
        }

        // Tentukan jenis karya
        $jenisKarya = null;
        if ($request->hasFile('video_karya')) {
            $jenisKarya = 'video';
        } elseif ($request->hasFile('dokumen_karya')) {
            $jenisKarya = 'dokumen';
        } elseif ($request->hasFile('gambar_karya')) {
            $jenisKarya = 'gambar';
        }

        // Upload file ke Supabase
        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'slug' => Str::slug($request->judul) . '-' . Str::random(6),
            'jenis_karya' => $jenisKarya,
            'tahun' => date('Y'),
            'user_id' => $request->user_id,
            'status' => 'MENUNGGU',
            'tanggal_pengajuan' => now()->setTimezone('Asia/Jakarta'),
        ];

        // Upload video jika ada
        if ($request->hasFile('video_karya')) {
            $videoPath = $this->uploadToSupabase($request->file('video_karya'), 'videos');
            $data['video_karya'] = $videoPath;
        }

        // Upload dokumen jika ada
        if ($request->hasFile('dokumen_karya')) {
            $docPath = $this->uploadToSupabase($request->file('dokumen_karya'), 'documents');
            $data['dokumen_karya'] = $docPath;
        }

        // Buat karya
        $karya = Karya::create($data);

        // Upload gambar jika ada
        if ($request->hasFile('gambar_karya')) {
            foreach ($request->file('gambar_karya') as $gambar) {
                $gambarPath = $this->uploadToSupabase($gambar, 'images');
                GambarKarya::create([
                    'karya_id' => $karya->id,
                    'nama_gambar' => $gambarPath
                ]);
            }
        }

        return redirect()->route('admin.karya.create')->with('success', 'Karya berhasil diunggah! Menunggu persetujuan admin.');
    }

    private function uploadToSupabase($file, $folder)
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->getRealPath();
        $fileSize = $file->getSize();
        $chunkSize = 5 * 1024 * 1024; // 5MB per chunk
        
        $supabaseStorageUrl = config('services.supabase.url') . '/storage/v1/object/' . 
                            config('services.supabase.bucket') . '/' . $folder . '/' . $fileName;
        
        // Jika file kecil, upload langsung
        if ($fileSize <= $chunkSize) {
            $fileStream = fopen($filePath, 'r');
            
            $response = Http::timeout(300)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . config('services.supabase.api_key'),
                    'Content-Type' => $file->getMimeType(),
                    'Content-Length' => $fileSize,
                ])
                ->withBody($fileStream, $file->getMimeType())
                ->put($supabaseStorageUrl);
                
            if (is_resource($fileStream)) {
                fclose($fileStream);
            }
        } else {
            // Untuk file besar, gunakan upload chunk
            $response = $this->uploadInChunks($filePath, $supabaseStorageUrl, $file->getMimeType(), $fileSize, $chunkSize);
        }
        
        if (!$response->successful()) {
            throw new \Exception('Gagal mengunggah file ke Supabase: ' . $response->body());
        }
        
        return $supabaseStorageUrl;
    }

    private function uploadInChunks($filePath, $url, $mimeType, $totalSize, $chunkSize)
    {
        $handle = fopen($filePath, 'r');
        $offset = 0;
        
        while (!feof($handle)) {
            $chunk = fread($handle, $chunkSize);
            $chunkLength = strlen($chunk);
            
            $headers = [
                'Authorization' => 'Bearer ' . config('services.supabase.api_key'),
                'Content-Type' => $mimeType,
                'Content-Length' => $chunkLength,
                'Content-Range' => 'bytes ' . $offset . '-' . ($offset + $chunkLength - 1) . '/' . $totalSize,
            ];
            
            $response = Http::timeout(120)
                ->withHeaders($headers)
                ->withBody($chunk, $mimeType)
                ->put($url);
                
            if (!$response->successful()) {
                fclose($handle);
                throw new \Exception('Gagal mengunggah chunk: ' . $response->body());
            }
            
            $offset += $chunkLength;
        }
        
        fclose($handle);
        return $response;
    }

    public function validasiIndex()
    {
        $tab = request()->get('tab', 'all');
        
        $query = Karya::with(['user', 'kategori', 'tags', 'gambarKaryas'])
                    ->latest();
        
        switch ($tab) {
            case 'video':
                $query->where('jenis_karya', 'video');
                break;
            case 'dokumen':
                $query->where('jenis_karya', 'dokumen');
                break;
            case 'gambar':
                $query->where('jenis_karya', 'gambar');
                break;
            case 'menunggu':
                $query->where('status', 'MENUNGGU');
                break;
            case 'diterima':
                $query->where('status', 'DISETUJUI');
                break;
            case 'ditolak':
                $query->where('status', 'DITOLAK');
                break;
            case 'perbaikan':
                $query->where('status', 'PERBAIKAN');
                break;
            case 'all':
            default:
                // No additional filtering
                break;
        }
        
        $karyas = $query->paginate(12);
        $kategoris = Kategori::all();
        $tags = Tag::all();
        
        return view('pages.admin.karya.index', compact('karyas', 'kategoris', 'tags', 'tab'));
    }

    public function show(Karya $karya)
    {
        $karya->load(['user', 'kategori', 'tags', 'gambarKaryas']);
        $kategoris = Kategori::all();
        $tags = Tag::all();
        
        return view('pages.admin.karya.show', compact('karya', 'kategoris', 'tags'));
    }

    public function updateStatus(Request $request, Karya $karya)
{
    // Validate common fields
    $request->validate([
        'status' => 'required|in:MENUNGGU,DISETUJUI,DITOLAK,PERBAIKAN',
    ]);

    // Initialize update data array
    $updateData = ['status' => $request->status];

    // Handle each status case separately
    switch ($request->status) {
        case 'DISETUJUI':
            $request->validate([
                'tanggal_publish' => 'required|date|after_or_equal:today',
                'kategori_id' => 'required|exists:kategoris,id',
                'tags' => 'nullable|array|max:5',
                'tags.*' => 'exists:tags,id'
            ]);

            $updateData['tanggal_publish'] = $request->tanggal_publish;
            $updateData['kategori_id'] = $request->kategori_id;
            $updateData['keterangan_revisi'] = null; // Clear revision notes if approved
            
            // Sync tags only if status is DISETUJUI
            if ($request->has('tags')) {
                $karya->tags()->sync($request->tags);
            } else {
                $karya->tags()->detach(); // Remove all tags if none selected
            }
            break;

        case 'PERBAIKAN':
            $request->validate([
                'keterangan_revisi' => 'required|string',
            ]);

            $updateData['keterangan_revisi'] = $request->keterangan_revisi;
            $updateData['tanggal_publish'] = null; // Clear publish date if set
            break;

        case 'DITOLAK':
            // Clear unnecessary fields when rejected
            $updateData['keterangan_revisi'] = null;
            $updateData['tanggal_publish'] = null;
            $updateData['kategori_id'] = null;
            $karya->tags()->detach(); // Remove all tags
            break;
    }

    // Update the karya
    $karya->update($updateData);

    return redirect()->route('admin.admin.karya.index')
                    ->with('success', 'Status karya berhasil diperbarui');
}
}