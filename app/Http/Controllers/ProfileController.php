<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'place_of_birth' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Laki-Laki,Perempuan,Lainnya',
            'religion' => 'nullable|string|max:50',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'provinces' => 'nullable|string',  // Nama provinsi
            'provincesId' => 'nullable|string', // ID provinsi
            'regencies' => 'nullable|string',   // Nama kabupaten
            'regenciesId' => 'nullable|string', // ID kabupaten
            'districts' => 'nullable|string',   // Nama kecamatan
            'districtsId' => 'nullable|string', // ID kecamatan
            'villages' => 'nullable|string',    // Nama desa
            'villagesId' => 'nullable|string',  // ID desa
        ]);

        if ($request->hasFile('profile_photo')) {
        try {
            $file = $request->file('profile_photo');
            
            // Generate unique filename while preserving original extension
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->getRealPath();
            
            $supabaseStorageUrl = config('services.supabase.url') . '/storage/v1/object/' . 
                                config('services.supabase.bucket') . '/' . $fileName;
            
            // Create a proper file stream to preserve file integrity
            $fileStream = fopen($filePath, 'r');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.supabase.api_key'),
                'Content-Type' => $file->getMimeType(),
                'Content-Length' => $file->getSize(),
            ])->withBody($fileStream, $file->getMimeType())
              ->put($supabaseStorageUrl);
            
            // Close the file stream
            if (is_resource($fileStream)) {
                fclose($fileStream);
            }
            
            if ($response->successful()) {
                $validated['profile_photo'] = $supabaseStorageUrl;
                
                // Delete old photo if exists (only for authenticated users)
                if ($user && $user->profile_photo) {
                    $this->deleteSupabaseFile($user->profile_photo);
                }
            } else {
                throw new \Exception('Supabase upload failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Profile photo upload error: ' . $e->getMessage());
            return back()->with('error', 'File upload failed: ' . $e->getMessage());
        }
        }

        try {
            $user->update($validated);
            return back()->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:8|confirmed|different:current_password',
    ], [
        'current_password.required' => 'Password saat ini wajib diisi',
        'new_password.required' => 'Password baru wajib diisi',
        'new_password.min' => 'Password baru minimal 8 karakter',
        'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        'new_password.different' => 'Password baru harus berbeda dengan password saat ini',
    ]);

    $user = Auth::user();

    try {
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Password saat ini tidak sesuai')
                ->withInput(); // <-- Hapus ini jika menyebabkan masalah
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        Auth::logout();
        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login kembali dengan password baru Anda.');
            
    } catch (\Exception $e) {
        Log::error('Error updating password: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Gagal mengubah password: ' . $e->getMessage());
    }
}
    /**
 * Helper function to delete file from Supabase storage
 */
private function deleteSupabaseFile(string $fileUrl)
{
    try {
        $fileName = basename(parse_url($fileUrl, PHP_URL_PATH));
        $deleteUrl = config('services.supabase.url') . '/storage/v1/object/' . 
                    config('services.supabase.bucket') . '/' . $fileName;
        
        Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.supabase.api_key')
        ])->delete($deleteUrl);
    } catch (\Exception $e) {
        Log::error('Failed to delete Supabase file: ' . $e->getMessage());
    }
}
}