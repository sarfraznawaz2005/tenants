<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();
        return view('tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'cnic' => 'required',
            'address' => 'required',
            'lease_date' => 'required|date',
            'monthly_rent' => 'required|numeric',
        ]);

        Tenant::create($request->all());

        return redirect()->route('tenants.index')->with('success', 'Tenant created successfully.');
    }

    public function show(Tenant $tenant)
    {
        return view('tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        return view('tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'cnic' => 'required',
            'address' => 'required',
            'lease_date' => 'required|date',
            'monthly_rent' => 'required|numeric',
        ]);

        $tenant->update($request->all());

        return redirect()->route('tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        foreach ($tenant->rents as $rent) {
            $rent->comments()->delete();
            $rent->delete();
        }
        $tenant->delete();

        return redirect()->route('tenants.index')->with('success', 'Tenant and all associated data deleted successfully.');
    }
}
