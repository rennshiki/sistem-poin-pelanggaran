<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ViolationType;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $teacher = auth()->user()->teacher;

        // Statistik guru ini
        $totalViolationsAdded = $teacher
            ? $teacher->violations()->count()
            : 0;

        $thisMonthCount = $teacher
            ? $teacher->violations()
                ->whereMonth('violation_date', now()->month)
                ->whereYear('violation_date', now()->year)
                ->count()
            : 0;

        // Daftar siswa dengan total poin (cepat)
        $students = Student::with(['user', 'violations'])
            ->get()
            ->map(function ($s) {
                $s->total_pts = $s->violations->sum('points');
                return $s;
            })
            ->sortByDesc('total_pts')
            ->values();

        // Pelanggaran terbaru yang dicatat guru ini
        $recentViolations = $teacher
            ? $teacher->violations()
                ->with(['student.user', 'violationType'])
                ->latest()
                ->take(5)
                ->get()
            : collect();

        $violationTypes = ViolationType::active()->orderBy('name')->get();

        return view('guru.dashboard', compact(
            'teacher',
            'totalViolationsAdded',
            'thisMonthCount',
            'students',
            'recentViolations',
            'violationTypes',
        ));
    }
}

