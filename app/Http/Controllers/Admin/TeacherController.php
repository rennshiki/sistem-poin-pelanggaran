<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $teachers = Teacher::with('user')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            })
            ->orderBy('created_at')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.teachers.index', compact('teachers', 'search'));
    }

    public function create(): View
    {
        return view('admin.teachers.create');
    }

    public function edit(Teacher $teacher): View
    {
        $teacher->load('user');

        return view('admin.teachers.create', compact('teacher'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:50', 'unique:users,nip', 'unique:teachers,nip'],
            'password' => ['required', 'string', 'min:6'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $photoPath = $request->file('profile_photo')?->store('profile-photos', 'public');

        DB::transaction(function () use ($validated, $photoPath) {
            $user = User::create([
                'name' => $validated['name'],
                'nip' => $validated['nip'],
                'role' => 'guru',
                'password' => $validated['password'],
                'is_active' => true,
                'profile_photo_path' => $photoPath,
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'name' => $validated['name'],
            ]);
        });

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'nip')->ignore($teacher->user_id),
                Rule::unique('teachers', 'nip')->ignore($teacher->id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $oldPhotoPath = $teacher->user?->profile_photo_path;
        $photoPath = $request->file('profile_photo')?->store('profile-photos', 'public');

        DB::transaction(function () use ($validated, $teacher, $photoPath) {
            $teacher->update([
                'name' => $validated['name'],
                'nip' => $validated['nip'],
            ]);

            $userChanges = [
                'name' => $validated['name'],
                'nip' => $validated['nip'],
            ];

            if (! empty($validated['password'])) {
                $userChanges['password'] = $validated['password'];
            }
            if ($photoPath) {
                $userChanges['profile_photo_path'] = $photoPath;
            }

            $teacher->user->update($userChanges);
        });

        if ($photoPath && $oldPhotoPath) {
            Storage::disk('public')->delete($oldPhotoPath);
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $photoPath = $teacher->user?->profile_photo_path;

        DB::transaction(function () use ($teacher) {
            $user = $teacher->user;

            if ($user) {
                $user->delete();
            } else {
                $teacher->delete();
            }
        });

        if ($photoPath) {
            Storage::disk('public')->delete($photoPath);
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Data guru dan akun loginnya berhasil dihapus.');
    }
}
