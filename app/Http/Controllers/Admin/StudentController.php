<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $students = Student::with('user')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%");
                })->orWhere('class', 'like', "%{$search}%")
                  ->orWhere('major', 'like', "%{$search}%");
            })
            ->orderBy('created_at')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.students.index', compact('students', 'search'));
    }

    public function create(): View
    {
        return view('admin.students.create');
    }

    public function edit(Student $student): View
    {
        $student->load('user');

        return view('admin.students.create', compact('student'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:20', 'unique:users,nisn'],
            'class' => ['required', 'string', 'max:50'],
            'major' => ['required', 'string', 'max:100'],
            'parent_phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $photoPath = $request->file('profile_photo')?->store('profile-photos', 'public');

        DB::transaction(function () use ($validated, $photoPath) {
            $user = User::create([
                'name' => $validated['name'],
                'nisn' => $validated['nisn'],
                'role' => 'siswa',
                'password' => $validated['password'],
                'is_active' => true,
                'profile_photo_path' => $photoPath,
            ]);

            Student::create([
                'user_id' => $user->id,
                'class' => $validated['class'],
                'major' => $validated['major'],
                'parent_phone' => $validated['parent_phone'],
            ]);
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:20', Rule::unique('users', 'nisn')->ignore($student->user_id)],
            'class' => ['required', 'string', 'max:50'],
            'major' => ['required', 'string', 'max:100'],
            'parent_phone' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:6'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $oldPhotoPath = $student->user?->profile_photo_path;
        $photoPath = $request->file('profile_photo')?->store('profile-photos', 'public');

        DB::transaction(function () use ($validated, $student, $photoPath) {
            $student->update([
                'class' => $validated['class'],
                'major' => $validated['major'],
                'parent_phone' => $validated['parent_phone'],
            ]);

            $userChanges = [
                'name' => $validated['name'],
                'nisn' => $validated['nisn'],
            ];

            if (! empty($validated['password'])) {
                $userChanges['password'] = $validated['password'];
            }
            if ($photoPath) {
                $userChanges['profile_photo_path'] = $photoPath;
            }

            $student->user->update($userChanges);
        });

        if ($photoPath && $oldPhotoPath) {
            Storage::disk('public')->delete($oldPhotoPath);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $photoPath = $student->user?->profile_photo_path;

        DB::transaction(function () use ($student) {
            $user = $student->user;

            if ($user) {
                $user->delete();
            } else {
                $student->delete();
            }
        });

        if ($photoPath) {
            Storage::disk('public')->delete($photoPath);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa dan akun loginnya berhasil dihapus.');
    }
}
