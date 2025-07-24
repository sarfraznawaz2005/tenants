<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use App\Models\Tenant;
use App\Models\Bill;
use Illuminate\Http\Request;

class RentController extends Controller
{
    public function index()
    {
        $rents = Rent::with('tenant')->orderBy('id', 'desc')->get();
        return view('rents.index', compact('rents'));
    }

    public function create()
    {
        $tenants = Tenant::all();
        $bills = Bill::orderBy('id', 'desc')->get();
        return view('rents.create', compact('tenants', 'bills'));
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
            'bill_id' => 'nullable|exists:bills,id',
            'due_date' => 'nullable|date',
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
        $bills = Bill::orderBy('id', 'desc')->get();
        return view('rents.edit', compact('rent', 'tenants', 'bills'));
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
            'bill_id' => 'nullable|exists:bills,id',
            'due_date' => 'nullable|date',
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

    public function invoice(Rent $rent)
    {
        $rent->load('tenant', 'bill.billType');
        $rentMonth = \Carbon\Carbon::parse($rent->date);

        return view('rents.invoice', compact('rent', 'rentMonth'));
    }
}
