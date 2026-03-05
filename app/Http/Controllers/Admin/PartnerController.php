<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $partners = Partner::latest()->paginate(10)->onEachSide(1);

        if ($request->ajax()) {
            return response()->json([
                'grid' => view('admin.partners.partials.partner-grid', compact('partners'))->render(),
            ]);
        }

        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.partners.create-modal')->render()
            ]);
        }
        return view('admin.partners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'alamat' => 'nullable|string',
                'deskripsi' => 'nullable|string',
            ]);

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('partners', 'public');
                $validated['logo'] = $path;
            }

            Partner::create($validated);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Partner berhasil ditambahkan']);
            }

            return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil ditambahkan');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Partner store failed', ['error' => $e->getMessage()]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data partner.',
                    'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : []
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $partner = Partner::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $path = $request->file('logo')->store('partners', 'public');
            $validated['logo'] = $path;
        }

        $partner->update($validated);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $partner = Partner::findOrFail($id);

        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Partner berhasil dihapus']);
        }

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus');
    }
}
