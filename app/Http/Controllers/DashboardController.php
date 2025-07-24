<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Rent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTenants = Tenant::count();
        $totalAmountReceived = Rent::sum('amount_received');
        $totalAmountRemaining = Rent::sum('amount_remaining');

        return view('dashboard', compact('totalTenants', 'totalAmountReceived', 'totalAmountRemaining'));
    }
}
