<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Data profil lengkap user yang login.
     * GET /api/me
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user'       => $user,
            'minat'      => $user->interests()->pluck('minat'),
            'pin_count'  => $user->pins()->count(),
            'board_count' => $user->boards()->count(),
        ]);
    }

    /**
     * Edit profil.
     * PATCH /api/me
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'nullable|string|max:100',
            'username' => 'nullable|string|max:50|unique:users,username,' . $request->user()->id,
            'bio'      => 'nullable|string|max:500',
            'website'  => 'nullable|string|max:255',
        ]);

        $user = $request->user();

        $user->update($request->only(['name', 'username', 'bio', 'website']));

        return response()->json([
            'user'    => $user->fresh(),
            'message' => 'Profil berhasil diperbarui.',
        ]);
    }
}
