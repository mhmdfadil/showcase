<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Admin-only methods
    public function index()
    {
        $role = request('role');
        
        if ($role === 'Mahasiswa') {
            $users = User::where('roles', 'Mahasiswa')->get();
        } else {
            $users = User::where('roles', 'Admin')->get();
        }
        
        return view('pages.admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('pages.admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => ['required', Rule::in(['Admin', 'Mahasiswa'])],
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'place_of_birth' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Laki-Laki,Perempuan,Lainnya',
            'religion' => 'nullable|string|max:50',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'provinces' => 'nullable|string',
            'provincesId' => 'nullable|string',
            'regencies' => 'nullable|string',
            'regenciesId' => 'nullable|string',
            'districts' => 'nullable|string',
            'districtsId' => 'nullable|string',
            'villages' => 'nullable|string',
            'villagesId' => 'nullable|string',
        ]);

        try {
            if ($request->hasFile('profile_photo')) {
                $validated['profile_photo'] = $this->uploadProfilePhoto($request->file('profile_photo'));
            }

            $validated['password'] = Hash::make($validated['password']);
            
            User::create($validated);

            return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        return view('pages.admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'roles' => ['required', Rule::in(['Admin', 'Mahasiswa'])],
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'place_of_birth' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Laki-Laki,Perempuan,Lainnya',
            'religion' => 'nullable|string|max:50',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'provinces' => 'nullable|string',
            'provincesId' => 'nullable|string',
            'regencies' => 'nullable|string',
            'regenciesId' => 'nullable|string',
            'districts' => 'nullable|string',
            'districtsId' => 'nullable|string',
            'villages' => 'nullable|string',
            'villagesId' => 'nullable|string',
        ]);

        try {
            if ($request->hasFile('profile_photo')) {
                $validated['profile_photo'] = $this->uploadProfilePhoto($request->file('profile_photo'));
                
                // Delete old photo if exists
                if ($user->profile_photo) {
                    $this->deleteSupabaseFile($user->profile_photo);
                }
            }

            $user->update($validated);

            return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            // Prevent self-deletion
            if ($user->id === Auth::id()) {
                return back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
            }

            // Delete profile photo if exists
            if ($user->profile_photo) {
                $this->deleteSupabaseFile($user->profile_photo);
            }

            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return back()->with('success', 'Password berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui password: ' . $e->getMessage());
        }
    }

    // Profile methods (accessible by all authenticated users)
    public function showProfile()
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
            'provinces' => 'nullable|string',
            'provincesId' => 'nullable|string',
            'regencies' => 'nullable|string',
            'regenciesId' => 'nullable|string',
            'districts' => 'nullable|string',
            'districtsId' => 'nullable|string',
            'villages' => 'nullable|string',
            'villagesId' => 'nullable|string',
        ]);

        try {
            if ($request->hasFile('profile_photo')) {
                $validated['profile_photo'] = $this->uploadProfilePhoto($request->file('profile_photo'));
                
                // Delete old photo if exists
                if ($user->profile_photo) {
                    $this->deleteSupabaseFile($user->profile_photo);
                }
            }

            $user->update($validated);

            return back()->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function updateProfilePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        $user = Auth::user();

        try {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Password saat ini tidak sesuai');
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            Auth::logout();
            return redirect()->route('login')
                ->with('success', 'Password berhasil diubah. Silakan login kembali dengan password baru Anda.');
                
        } catch (\Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }

    // Helper methods
    private function uploadProfilePhoto($file)
    {
        try {
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->getRealPath();
            
            $supabaseStorageUrl = config('services.supabase.url') . '/storage/v1/object/' . 
                                config('services.supabase.bucket') . '/' . $fileName;
            
            $fileStream = fopen($filePath, 'r');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.supabase.api_key'),
                'Content-Type' => $file->getMimeType(),
                'Content-Length' => $file->getSize(),
            ])->withBody($fileStream, $file->getMimeType())
              ->put($supabaseStorageUrl);
            
            if (is_resource($fileStream)) {
                fclose($fileStream);
            }
            
            if ($response->successful()) {
                return $supabaseStorageUrl;
            }
            
            throw new \Exception('Supabase upload failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Profile photo upload error: ' . $e->getMessage());
            throw $e;
        }
    }

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