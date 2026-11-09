<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load(['student', 'teacher']);

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    public function photo(User $user)
    {
        $requestUser = request()->user();
        abort_unless($requestUser && ($requestUser->id === $user->id || $requestUser->role === 'admin'), 403);
        abort_unless($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path), 404);

        return Storage::disk('public')->response($user->profile_photo_path);
    }
}
