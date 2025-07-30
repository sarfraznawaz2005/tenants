<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Rent;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        if ($request->filled('bill_id')) {
            $bill = Bill::find($request->bill_id);
            if ($bill && $bill->picture) {
                $tenant = Tenant::find($request->tenant_id);
                $tenantNameSlug = Str::slug($tenant->name);
                $originalPath = $bill->picture;
                $fileName = basename($originalPath);
                $newPath = 'bills/' . $tenantNameSlug . '/' . $fileName;

                if ($originalPath !== $newPath && Storage::disk('public')->exists($originalPath)) {
                    Storage::disk('public')->move($originalPath, $newPath);
                    $bill->update(['picture' => $newPath]);
                }
            }
        }

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

        $originalBillId = $rent->bill_id;
        $newBillId = $request->input('bill_id');

        // Handle disassociation of the old bill
        if ($originalBillId && $originalBillId != $newBillId) {
            $oldBill = Bill::find($originalBillId);
            if ($oldBill && $oldBill->picture) {
                $oldPath = $oldBill->picture;
                $fileName = basename($oldPath);
                $newPathForOldBill = 'bills/' . $fileName;

                if (dirname($oldPath) !== 'bills' && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->move($oldPath, $newPathForOldBill);
                    $oldBill->update(['picture' => $newPathForOldBill]);
                }
            }
        }

        // Handle association of the new bill
        if ($newBillId) {
            $newBill = Bill::find($newBillId);
            if ($newBill && $newBill->picture) {
                $tenant = Tenant::find($request->tenant_id);
                $tenantNameSlug = Str::slug($tenant->name);
                $originalPath = $newBill->picture;
                $fileName = basename($originalPath);
                $newPathForNewBill = 'bills/' . $tenantNameSlug . '/' . $fileName;

                if ($originalPath !== $newPathForNewBill && Storage::disk('public')->exists($originalPath)) {
                    Storage::disk('public')->move($originalPath, $newPathForNewBill);
                    $newBill->update(['picture' => $newPathForNewBill]);
                }
            }
        }

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
            $rentMonthYear = \Carbon\Carbon::parse($rent->date)->format('F Y');
            $imageName = \Illuminate\Support\Str::slug('Rent for ' . $rentMonthYear) . '.png';
            $tenantName = \Illuminate\Support\Str::slug($rent->tenant->name);

            $path = 'invoices/' . $tenantName . '/' . $imageName;

            Storage::disk('public')->put($path, base64_decode($imageData));

            return response()->json(['success' => true, 'imageUrl' => Storage::disk('public')->url($path)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
