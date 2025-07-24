<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use App\Models\Tenant;
use Illuminate\Http\Request;

class RentController extends Controller
{
    public function index()
    {
        $rents = Rent::with('tenant')->get();
        return view('rents.index', compact('rents'));
    }

    public function create()
    {
        $tenants = Tenant::all();
        return view('rents.create', compact('tenants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'amount_due' => 'required|numeric',
            'amount_received' => 'required|numeric',
            'date' => 'required|date',
            'status' => 'required|in:paid,not_paid',
            'comment' => 'nullable|string',
        ]);

        $rent = new Rent($request->all());
        $rent->amount_remaining = $rent->amount_due - $rent->amount_received;
        $rent->save();

        return redirect()->route('rents.index')->with('success', 'Rent created successfully.');
    }

    public function show(Rent $rent)
    {
        $rent->load('comments');
        return view('rents.show', compact('rent'));
    }

    public function edit(Rent $rent)
    {
        $tenants = Tenant::all();
        return view('rents.edit', compact('rent', 'tenants'));
    }

    public function update(Request $request, Rent $rent)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'amount_due' => 'required|numeric',
            'amount_received' => 'required|numeric',
            'date' => 'required|date',
            'status' => 'required|in:paid,not_paid',
            'comment' => 'nullable|string',
        ]);

        $rent->fill($request->all());
        $rent->amount_remaining = $rent->amount_due - $rent->amount_received;
        $rent->save();

        return redirect()->route('rents.index')->with('success', 'Rent updated successfully.');
    }

    public function destroy(Rent $rent)
    {
        $rent->comments()->delete();
        $rent->delete();

        return redirect()->route('rents.index')->with('success', 'Rent and all associated comments deleted successfully.');
    }
}
