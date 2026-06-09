<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $apiUrl = 'http://localhost:5000/api';
    
    // =====================================================
    // TAMPILAN HALAMAN
    // =====================================================
    
    public function showLogin()
    {
        if (session('user')) {
            if (session('user')['role'] === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }
        return view('auth.login');
    }
    
    public function showRegister()
    {
        if (session('user')) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }
    
    public function showProfile()
    {
        if (!session('user')) {
            return redirect()->route('login');
        }
        return view('auth.profile');
    }
    
    // =====================================================
    // PROSES LOGIN & REGISTER
    // =====================================================
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        try {
            $response = Http::timeout(10)->post($this->apiUrl . '/auth/login', [
                'email' => $request->email,
                'password' => $request->password
            ]);
            
            $data = $response->json();
            
            if ($response->successful() && isset($data['success']) && $data['success'] === true) {
                // Simpan token dan user ke session dengan benar
                session([
                    'user' => $data['user'],
                    'token' => $data['token']
                ]);
                
                // Log untuk debugging
                Log::info('Login successful', [
                    'user_id' => $data['user']['id'],
                    'user_email' => $data['user']['email'],
                    'user_role' => $data['user']['role'],
                    'token_exists' => !empty($data['token']),
                    'token_length' => strlen($data['token'] ?? '')
                ]);
                
                if ($data['user']['role'] === 'admin') {
                    return redirect()->route('admin.dashboard')->with('success', 'Selamat datang Admin!');
                }
                return redirect()->route('home')->with('success', 'Login berhasil! Selamat berbelanja.');
            }
            
            return back()->with('error', $data['message'] ?? 'Email atau password salah')->withInput();
            
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()->with('error', 'Koneksi ke server gagal. Pastikan backend berjalan di port 5000.');
        }
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3',
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'phone'    => 'nullable|string',
            'address'  => 'nullable|string'
        ]);
        
        try {
            $response = Http::timeout(10)->post($this->apiUrl . '/auth/register', [
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone ?? '',
                'address' => $request->address ?? ''
            ]);
            
            $data = $response->json();
            
            if ($response->successful() && isset($data['success']) && $data['success'] === true) {
                // Auto login setelah register
                $loginResponse = Http::timeout(10)->post($this->apiUrl . '/auth/login', [
                    'email' => $request->email,
                    'password' => $request->password
                ]);
                
                $loginData = $loginResponse->json();
                
                if ($loginResponse->successful() && isset($loginData['success']) && $loginData['success'] === true) {
                    session([
                        'user' => $loginData['user'],
                        'token' => $loginData['token']
                    ]);
                    
                    Log::info('Register and auto login successful', [
                        'user_id' => $loginData['user']['id'],
                        'user_email' => $loginData['user']['email']
                    ]);
                }
                
                return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang di HP Market.');
            }
            
            return back()->with('error', $data['message'] ?? 'Registrasi gagal. Email mungkin sudah terdaftar.')->withInput();
            
        } catch (\Exception $e) {
            Log::error('Register error: ' . $e->getMessage());
            return back()->with('error', 'Koneksi ke server gagal. Pastikan backend berjalan di port 5000.');
        }
    }
    
    // =====================================================
    // LOGOUT & UPDATE PROFILE
    // =====================================================
    
    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Berhasil logout. Sampai jumpa kembali!');
    }
    
    public function updateProfile(Request $request)
    {
        if (!session('user')) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'phone' => 'nullable|string',
            'address' => 'nullable|string'
        ]);
        
        // Update data di session
        $user = session('user');
        $user['phone'] = $request->phone;
        $user['address'] = $request->address;
        session(['user' => $user]);
        
        Log::info('Profile updated', ['user_id' => $user['id']]);
        
        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}