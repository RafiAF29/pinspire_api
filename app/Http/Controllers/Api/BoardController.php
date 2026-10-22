<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Pin;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Daftar papan milik user yang login.
     * GET /api/boards
     */
    public function index(Request $request)
    {
        $boards = $request->user()
            ->boards()
            ->with('pins.user:id,name,username,avatar_url')
            ->latest()
            ->get();

        return response()->json($boards);
    }

    /**
     * Buat papan baru.
     * POST /api/boards
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'is_private' => 'nullable|boolean',
        ]);

        $board = Board::create([
            'user_id'    => $request->user()->id,
            'name'       => $request->name,
            'is_private' => $request->boolean('is_private', false),
        ]);

        return response()->json($board, 201);
    }

    /**
     * Hapus papan.
     * DELETE /api/boards/{board}
     */
    public function destroy(Request $request, Board $board)
    {
        if ($board->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        $board->delete();

        return response()->json(['message' => 'Papan dihapus.']);
    }

    /**
     * Tambah pin ke papan.
     * POST /api/boards/{board}/pins/{pin}
     */
    public function addPin(Request $request, Board $board, Pin $pin)
    {
        if ($board->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        $board->pins()->syncWithoutDetaching([$pin->id]);

        return response()->json(['message' => 'Pin ditambahkan ke papan.']);
    }

    /**
     * Hapus pin dari papan.
     * DELETE /api/boards/{board}/pins/{pin}
     */
    public function removePin(Request $request, Board $board, Pin $pin)
    {
        if ($board->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        $board->pins()->detach($pin->id);

        return response()->json(['message' => 'Pin dihapus dari papan.']);
    }
}
