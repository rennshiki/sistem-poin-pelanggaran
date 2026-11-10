<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $teacher = auth()->user()->teacher;
        $search = trim((string) $request->query('search', ''));

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
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('nisn', 'like', "%{$search}%");
                    })
                    ->orWhere('class', 'like', "%{$search}%")
                    ->orWhere('major', 'like', "%{$search}%");
                });
            })
            ->whereHas('violations')
            ->get()
            ->map(function ($s) {
                $s->total_pts = $s->violations->sum('points');
                return $s;
            })
            ->sortByDesc('total_pts')
            ->values();

        return view('teachers.dashboard', compact(
            'teacher',
            'totalViolationsAdded',
            'thisMonthCount',
            'students',
            'search',
        ));
    }
}

