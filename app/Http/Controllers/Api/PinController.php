<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pin;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PinController extends Controller
{
    /**
     * Feed beranda — pin terbaru, dengan data user.
     * GET /api/pins/feed
     */
    public function feed(Request $request)
    {
        $pins = Pin::with('user:id,name,username,avatar_url')
            ->latest()
            ->paginate($request->input('per_page', 20));

        return response()->json($pins);
    }

    /**
     * Upload pin baru (multipart/form-data).
     * POST /api/pins
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'link_url'    => 'nullable|url|max:500',
            'image'       => 'required|image|max:10240', // maks 10MB
            'aspect'      => 'nullable|numeric|min:0.3|max:3',
            'board_id'    => 'nullable|exists:boards,id',
        ]);

        // Simpan gambar ke storage/app/public/pins/.
        $path = $request->file('image')->store('pins', 'public');
        $imageUrl = url('storage/' . $path);

        $pin = Pin::create([
            'user_id'     => $request->user()->id,
            'title'       => $request->title,
            'description' => $request->description,
            'link_url'    => $request->link_url,
            'image_url'   => $imageUrl,
            'aspect'      => $request->input('aspect', 1.0),
            'ai_modified' => $request->boolean('ai_modified', false),
        ]);

        // Tambah ke board jika diminta.
        if ($request->board_id) {
            $board = Board::find($request->board_id);
            if ($board && $board->user_id === $request->user()->id) {
                $board->pins()->attach($pin->id);
            }
        }

        $pin->load('user:id,name,username,avatar_url');

        return response()->json($pin, 201);
    }

    /**
     * Pin terkait (acak, kecuali pin ini sendiri).
     * GET /api/pins/{pin}/related
     */
    public function related(Pin $pin)
    {
        $related = Pin::with('user:id,name,username,avatar_url')
            ->where('id', '!=', $pin->id)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return response()->json($related);
    }

    /**
     * Like pin.
     * POST /api/pins/{pin}/like
     */
    public function like(Request $request, Pin $pin)
    {
        $request->user()->likedPins()->syncWithoutDetaching([$pin->id]);

        return response()->json([
            'liked'       => true,
            'likes_count' => $pin->likedBy()->count(),
        ]);
    }

    /**
     * Unlike pin.
     * DELETE /api/pins/{pin}/like
     */
    public function unlike(Request $request, Pin $pin)
    {
        $request->user()->likedPins()->detach($pin->id);

        return response()->json([
            'liked'       => false,
            'likes_count' => $pin->likedBy()->count(),
        ]);
    }

    /**
     * Simpan pin.
     * POST /api/pins/{pin}/save
     */
    public function save(Request $request, Pin $pin)
    {
        $request->user()->savedPins()->syncWithoutDetaching([$pin->id]);

        return response()->json(['saved' => true]);
    }

    /**
     * Batal simpan pin.
     * DELETE /api/pins/{pin}/save
     */
    public function unsave(Request $request, Pin $pin)
    {
        $request->user()->savedPins()->detach($pin->id);

        return response()->json(['saved' => false]);
    }

    /**
     * Daftar pin yang disimpan user.
     * GET /api/pins/saved
     */
    public function saved(Request $request)
    {
        $pins = $request->user()
            ->savedPins()
            ->with('user:id,name,username,avatar_url')
            ->latest('saved_pins.saved_at')
            ->get();

        return response()->json($pins);
    }
}
