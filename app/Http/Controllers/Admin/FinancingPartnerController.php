<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancingPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinancingPartnerController extends Controller
{
    public function index()
    {
        $partners = FinancingPartner::ordered()->paginate(15);
        return view('admin.financing-partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.financing-partners.form', ['partner' => new FinancingPartner()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'website_url' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;
        $validated['logo'] = $request->file('logo')->store('financing-partners', 'public');

        FinancingPartner::create($validated);

        return redirect()->route('admin.financing-partners.index')
            ->with('success', 'Financing partner created successfully.');
    }

    public function edit(FinancingPartner $financing_partner)
    {
        return view('admin.financing-partners.form', ['partner' => $financing_partner]);
    }

    public function update(Request $request, FinancingPartner $financing_partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'website_url' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($financing_partner->logo) {
                Storage::disk('public')->delete($financing_partner->logo);
            }
            $validated['logo'] = $request->file('logo')->store('financing-partners', 'public');
        }

        $financing_partner->update($validated);

        return redirect()->route('admin.financing-partners.index')
            ->with('success', 'Financing partner updated successfully.');
    }

    public function destroy(FinancingPartner $financing_partner)
    {
        if ($financing_partner->logo) {
            Storage::disk('public')->delete($financing_partner->logo);
        }
        
        $financing_partner->delete();

        return redirect()->route('admin.financing-partners.index')
            ->with('success', 'Financing partner deleted successfully.');
    }

    public function toggleActive(FinancingPartner $financing_partner)
    {
        $financing_partner->update(['is_active' => !$financing_partner->is_active]);
        
        return back()->with('success', 
            'Partner ' . ($financing_partner->is_active ? 'activated' : 'deactivated') . ' successfully.');
    }
}
