<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register mahasiswa
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'email',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    $allowedDomains = ['@student.telkomuniversity.ac.id', '@telkomuniversity.ac.id'];
                    $isValid = false;
                    
                    foreach ($allowedDomains as $domain) {
                        if (str_ends_with($value, $domain)) {
                            $isValid = true;
                            break;
                        }
                    }
                    
                    if (!$isValid) {
                        $fail('Email harus menggunakan domain @student.telkomuniversity.ac.id atau @telkomuniversity.ac.id');
                    }
                },
            ],
            'password' => 'required|string|min:6|confirmed',
            'nim'      => 'required|string|unique:users,nim',
        ], [
        // Custom error messages
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'nim.required' => 'NIM wajib diisi.',
        'nim.unique' => 'NIM sudah terdaftar.',
    ]);

        // Tentukan role berdasarkan domain email
        $role = str_ends_with($data['email'], '@student.telkomuniversity.ac.id') 
                ? 'mahasiswa' 
                : 'dosen';

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'nim'      => $data['nim'],
            'role'     => $role,
        ]);

        // Login otomatis setelah register
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect berdasarkan role
        return $this->redirectBasedOnRole($user);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $allowedDomains = ['@student.telkomuniversity.ac.id', '@telkomuniversity.ac.id'];
                    $isValid = false;
                    
                    foreach ($allowedDomains as $domain) {
                        if (str_ends_with($value, $domain)) {
                            $isValid = true;
                            break;
                        }
                    }
                    
                    if (!$isValid) {
                        $fail('Email harus menggunakan domain @student.telkomuniversity.ac.id atau @telkomuniversity.ac.id');
                    }
                },
            ],
            'password' => 'required|string',
        ]);

        // Cek apakah email terdaftar
        $user = User::where('email', $data['email'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar. Silakan buat akun terlebih dahulu.',
            ])->onlyInput('email');
        }

        // Cek password
        if (!Hash::check($data['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'Password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // Login berhasil
        Auth::login($user);
        $request->session()->regenerate();
        
        return $this->redirectBasedOnRole($user);
    }

    private function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'mahasiswa':
                return redirect()->route('dashboard');
            case 'dosen':
                return redirect()->route('dosen.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    // ==================== API METHODS (UNTUK API ROUTES) ====================
    // Ini digunakan jika ada API routes terpisah
    
    public function me(Request $request)
    {
        return response()->json($request->user()->load('coordinatedCourses'));
    }

    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}