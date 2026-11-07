<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = auth()->user()->student;

        if (! $student) {
            abort(403, 'Data siswa tidak ditemukan.');
        }

        // Load violations with type
        $student->load(['violations.violationType', 'violations.teacher']);

        $totalPoints    = $student->violations->sum('points');
        $maxPoints      = 100;
        $percentage     = min(100, ($totalPoints / $maxPoints) * 100);

        // Hitung per kategori
        $pointsByCategory = $student->violations
            ->groupBy('violationType.category')
            ->map(fn($group) => $group->sum('points'));

        // Riwayat terbaru
        $recentViolations = $student->violations
            ->sortByDesc('violation_date')
            ->take(10)
            ->values();

        // Warna bar
        $barColor = match (true) {
            $totalPoints >= 75 => 'rose',
            $totalPoints >= 50 => 'amber',
            $totalPoints >= 25 => 'orange',
            default            => 'emerald',
        };

        return view('siswa.dashboard', compact(
            'student',
            'totalPoints',
            'maxPoints',
            'percentage',
            'barColor',
            'pointsByCategory',
            'recentViolations',
        ));
    }
}

