<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Violation;
use App\Models\ViolationType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ViolationController extends Controller
{
    public function students(Request $request): View
    {
        $search = $request->query('search');

        $students = Student::with('user')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('nisn', 'like', "%{$search}%");
                    })
                    ->orWhere('class', 'like', "%{$search}%")
                    ->orWhere('major', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('teachers.violations.students', compact('students', 'search'));
    }

    public function create(Request $request): View
    {
        $studentId = $request->query('student_id', old('student_id'));
        if (! $studentId) {
            return redirect()->route('guru.violations.students');
        }

        $selectedStudent = Student::with('user')->findOrFail($studentId);
        $violationTypes = ViolationType::active()->orderBy('category')->orderBy('name')->get();

        return view('teachers.violations.create', compact('violationTypes', 'selectedStudent'));
    }

    public function store(Request $request): RedirectResponse
    {
        $teacher = auth()->user()->teacher;

        if (! $teacher) {
            abort(403, 'Akun guru tidak ditemukan.');
        }

        $validated = $request->validate([
            'student_id'        => ['required', 'exists:students,id'],
            'category'          => ['required', 'in:ringan,sedang,berat'],
            'violation_type_id' => ['required', 'exists:violation_types,id'],
            'violation_date'    => ['required', 'date', 'before_or_equal:today'],
            'notes'             => ['nullable', 'string', 'max:500'],
            'photo'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $violationType = ViolationType::active()
            ->where('category', $validated['category'])
            ->find($validated['violation_type_id']);

        if (! $violationType) {
            return back()->withErrors([
                'violation_type_id' => 'Pilih pelanggaran yang sesuai dengan kategori.',
            ])->withInput();
        }

        $photoPath = $request->file('photo')?->store('violation-photos', 'public');

        Violation::create([
            'student_id'        => $validated['student_id'],
            'teacher_id'        => $teacher->id,
            'violation_type_id' => $validated['violation_type_id'],
            'points'            => ViolationType::pointsForCategory($violationType->category),
            'notes'             => $validated['notes'] ?? null,
            'photo_path'        => $photoPath,
            'violation_date'    => $validated['violation_date'],
        ]);

        return redirect()->route('guru.dashboard')
            ->with('success', 'Pelanggaran siswa berhasil dicatat.');
    }

    public function index(Request $request): View
    {
        $teacher = auth()->user()->teacher;
        $search  = $request->query('search');

        $violations = Violation::with(['student.user', 'violationType'])
            ->where('teacher_id', $teacher?->id)
            ->when($search, function ($q) use ($search) {
                $q->whereHas('student.user', fn($sq) => $sq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('violationType', fn($vq) => $vq->where('name', 'like', "%{$search}%"));
            })
            ->latest('violation_date')
            ->paginate(15)
            ->withQueryString();

        return view('teachers.violations.index', compact('violations', 'search'));
    }

    public function photo(Violation $violation)
    {
        $teacher = auth()->user()->teacher;

        if ($violation->teacher_id !== $teacher?->id) {
            abort(403);
        }

        abort_unless($violation->photo_path && Storage::disk('public')->exists($violation->photo_path), 404);

        return Storage::disk('public')->response($violation->photo_path);
    }

    public function destroy(Violation $violation): RedirectResponse
    {
        $teacher = auth()->user()->teacher;

        if ($violation->teacher_id !== $teacher?->id) {
            abort(403);
        }

        $violation->delete();
        if ($violation->photo_path) {
            Storage::disk('public')->delete($violation->photo_path);
        }

        return back()->with('success', 'Catatan pelanggaran berhasil dihapus.');
    }
}

