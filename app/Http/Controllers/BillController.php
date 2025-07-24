<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BillController extends Controller
{
    public function index()
    {
        $billTypes = BillType::orderBy('name')->get();
        $bills = Bill::with('billType')->orderBy('id', 'desc')->get();
        return view('bills.index', compact('billTypes', 'bills'));
    }

    public function storeType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        BillType::create($request->all());

        return redirect()->route('bills.index')->with('success', 'Bill type created successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bill_type_id' => 'required|exists:bill_types,id',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('bills', 'public');
        }

        Bill::create([
            'bill_type_id' => $request->bill_type_id,
            'date' => $request->date,
            'amount' => $request->amount,
            'picture' => $path,
        ]);

        return redirect()->route('bills.index')->with('success', 'Bill created successfully.');
    }

    public function edit(Bill $bill)
    {
        $billTypes = BillType::all();
        return view('bills.edit', compact('bill', 'billTypes'));
    }

    public function update(Request $request, Bill $bill)
    {
        $request->validate([
            'bill_type_id' => 'required|exists:bill_types,id',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $path = $bill->picture;
        if ($request->hasFile('picture')) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('picture')->store('bills', 'public');
        }

        $bill->update([
            'bill_type_id' => $request->bill_type_id,
            'date' => $request->date,
            'amount' => $request->amount,
            'picture' => $path,
        ]);

        return redirect()->route('bills.index')->with('success', 'Bill updated successfully.');
    }

    public function destroy(Bill $bill)
    {
        if ($bill->picture) {
            Storage::disk('public')->delete($bill->picture);
        }
        $bill->delete();
        return redirect()->route('bills.index')->with('success', 'Bill deleted successfully.');
    }

    public function editType(BillType $bill_type)
    {
        return view('bill-types.edit', compact('bill_type'));
    }

    public function updateType(Request $request, BillType $bill_type)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $bill_type->update($request->all());

        return redirect()->route('bills.index')->with('success', 'Bill type updated successfully.');
    }

    public function destroyType(BillType $bill_type)
    {
        $bill_type->delete();
        return redirect()->route('bills.index')->with('success', 'Bill type deleted successfully.');
    }
}
