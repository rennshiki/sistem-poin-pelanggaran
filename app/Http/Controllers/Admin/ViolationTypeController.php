<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViolationType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ViolationTypeController extends Controller
{
    public function index(Request $request): View
    {
        $search   = $request->query('search');
        $category = $request->query('category');

        $violationTypes = ViolationType::query()
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"))
            ->when($category, fn($q) => $q->where('category', $category))
            ->orderBy('created_at')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.violation-types.index', compact('violationTypes', 'search', 'category'));
    }

    public function create(): View
    {
        return view('admin.violation-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:violation_types,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category'    => ['required', 'in:ringan,sedang,berat'],
            'is_active'   => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['points'] = ViolationType::pointsForCategory($validated['category']);

        ViolationType::create($validated);

        return redirect()->route('admin.violation-types.index')
            ->with('success', 'Jenis pelanggaran berhasil ditambahkan.');
    }

    public function edit(ViolationType $violationType): View
    {
        return view('admin.violation-types.edit', compact('violationType'));
    }

    public function update(Request $request, ViolationType $violationType): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:violation_types,name,' . $violationType->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'category'    => ['required', 'in:ringan,sedang,berat'],
            'is_active'   => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['points'] = ViolationType::pointsForCategory($validated['category']);

        $violationType->update($validated);

        return redirect()->route('admin.violation-types.index')
            ->with('success', 'Jenis pelanggaran berhasil diperbarui.');
    }

    public function destroy(ViolationType $violationType): RedirectResponse
    {
        $violationType->delete();

        return redirect()->route('admin.violation-types.index')
            ->with('success', 'Jenis pelanggaran berhasil dihapus.');
    }
}
