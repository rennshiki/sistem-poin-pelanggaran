<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Violation;
use App\Models\ViolationType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ViolationController extends Controller
{
    public function create(Request $request): View
    {
        $students       = Student::with('user')->get()->sortBy(fn($s) => $s->user?->name);
        $violationTypes = ViolationType::active()->orderBy('category')->orderBy('name')->get();
        $selectedStudent = $request->query('student_id');

        return view('guru.violations.create', compact('students', 'violationTypes', 'selectedStudent'));
    }

    public function store(Request $request): RedirectResponse
    {
        $teacher = auth()->user()->teacher;

        if (! $teacher) {
            abort(403, 'Akun guru tidak ditemukan.');
        }

        $validated = $request->validate([
            'student_id'        => ['required', 'exists:students,id'],
            'violation_type_id' => ['required', 'exists:violation_types,id'],
            'violation_date'    => ['required', 'date', 'before_or_equal:today'],
            'notes'             => ['nullable', 'string', 'max:500'],
        ]);

        $violationType = ViolationType::findOrFail($validated['violation_type_id']);

        Violation::create([
            'student_id'        => $validated['student_id'],
            'teacher_id'        => $teacher->id,
            'violation_type_id' => $validated['violation_type_id'],
            'points'            => $violationType->points,
            'notes'             => $validated['notes'] ?? null,
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

        return view('guru.violations.index', compact('violations', 'search'));
    }

    public function destroy(Violation $violation): RedirectResponse
    {
        $teacher = auth()->user()->teacher;

        if ($violation->teacher_id !== $teacher?->id) {
            abort(403);
        }

        $violation->delete();

        return back()->with('success', 'Catatan pelanggaran berhasil dihapus.');
    }
}

