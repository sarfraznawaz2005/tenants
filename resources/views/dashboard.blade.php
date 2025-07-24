@extends('layouts.app')

@section('content')
    <h3>Dashboard</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Tenants</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalTenants }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Total Rents</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalRents }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Amount Received</div>
                <div class="card-body">
                    <h5 class="card-title">PKR {{ number_format($totalAmountReceived, 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Total Amount Remaining</div>
                <div class="card-body">
                    <h5 class="card-title">PKR {{ number_format($totalAmountRemaining, 2) }}</h5>
                </div>
            </div>
        </div>
    </div>
@endsection
