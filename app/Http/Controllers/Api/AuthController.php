<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Cek apakah email sudah terdaftar.
     * POST /api/auth/check-email
     */
    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $exists = User::where('email', strtolower($request->email))->exists();

        return response()->json(['terdaftar' => $exists]);
    }

    /**
     * Registrasi akun baru.
     * POST /api/auth/register
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'minat'    => 'nullable|array',
            'minat.*'  => 'string|max:100',
        ]);

        $email = strtolower($request->email);

        // Buat username dari nama (huruf kecil, alfanumerik saja).
        $baseUsername = strtolower(preg_replace('/[^a-z0-9]/i', '', $request->name));
        if (empty($baseUsername)) {
            $baseUsername = 'user';
        }
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'name'     => $request->name,
            'username' => $username,
            'email'    => $email,
            'password' => $request->password, // otomatis di-hash oleh cast
        ]);

        // Simpan minat jika ada.
        if ($request->has('minat') && is_array($request->minat)) {
            foreach ($request->minat as $minat) {
                UserInterest::create([
                    'user_id' => $user->id,
                    'minat'   => $minat,
                ]);
            }
        }

        $token = $user->createToken('pinspire')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
            'minat' => $user->interests()->pluck('minat'),
        ], 201);
    }

    /**
     * Login dengan email + password.
     * POST /api/auth/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', strtolower($request->email))->first();

        if (! $user) {
            return response()->json([
                'message' => 'Email ini belum terdaftar di Pinspire.',
            ], 401);
        }

        if ($user->password === null) {
            return response()->json([
                'message' => 'Akun ini terdaftar lewat Google. Gunakan "Masuk dengan Google".',
            ], 401);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Kata sandi salah. Coba lagi atau pilih "Lupa kata sandi?".',
            ], 401);
        }

        $token = $user->createToken('pinspire')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
            'minat' => $user->interests()->pluck('minat'),
        ]);
    }

    /**
     * Logout (hapus token saat ini).
     * POST /api/auth/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil keluar.']);
    }

    /**
     * Data akun yang sedang login.
     * GET /api/auth/me
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user'  => $user,
            'minat' => $user->interests()->pluck('minat'),
        ]);
    }
}
