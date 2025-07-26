<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use App\Models\Tenant;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'adjustments.*.name' => 'nullable|string',
            'adjustments.*.type' => 'nullable|in:plus,minus',
            'adjustments.*.amount' => 'nullable|numeric|min:0',
        ]);

        $rent = new Rent($request->all());
        $rent->amount_remaining = $rent->amount_due - $rent->amount_received;
        $rent->save();

        if ($request->has('adjustments')) {
            foreach ($request->input('adjustments') as $adjustmentData) {
                $rent->adjustments()->create($adjustmentData);
            }
        }

        return redirect()->route('rents.index')->with('success', 'Rent created successfully.');
    }

    public function show(Rent $rent)
    {
        $rent->load('comments', 'adjustments');
        return view('rents.show', compact('rent'));
    }

    public function edit(Rent $rent)
    {
        $tenants = Tenant::all();
        $bills = Bill::orderBy('id', 'desc')->get();
        $rent->load('adjustments');
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
            'adjustments.*.id' => 'nullable|exists:adjustments,id',
            'adjustments.*.name' => 'nullable|string',
            'adjustments.*.type' => 'nullable|in:plus,minus',
            'adjustments.*.amount' => 'nullable|numeric|min:0',
        ]);

        $rent->fill($request->all());
        $rent->amount_remaining = $rent->amount_due - $rent->amount_received;
        $rent->save();

        $currentAdjustmentIds = collect($request->input('adjustments'))->pluck('id')->filter()->all();
        $rent->adjustments()->whereNotIn('id', $currentAdjustmentIds)->delete();

        if ($request->has('adjustments')) {
            foreach ($request->input('adjustments') as $adjustmentData) {
                if (isset($adjustmentData['id'])) {
                    $rent->adjustments()->where('id', $adjustmentData['id'])->update($adjustmentData);
                } else {
                    $rent->adjustments()->create($adjustmentData);
                }
            }
        }

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
        $rent->load('tenant', 'bill.billType', 'adjustments');
        $rentMonth = \Carbon\Carbon::parse($rent->date);

        return view('rents.invoice', compact('rent', 'rentMonth'));
    }

    public function saveInvoiceImage(Request $request, Rent $rent)
    {
        $request->validate([
            'image' => 'required',
        ]);

        try {
            $imageData = $request->input('image');
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = 'invoice-' . $rent->id . '-' . time() . '.png';

            Storage::disk('public')->put('invoices/' . $imageName, base64_decode($imageData));

            return response()->json(['success' => true, 'imageUrl' => Storage::disk('public')->url('invoices/' . $imageName)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
