@extends('layouts.app')

@section('content')
    <h3>Edit Tenant</h3>
    <form action="{{ route('tenants.update', $tenant->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $tenant->name }}" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $tenant->phone }}" required>
        </div>
        <div class="mb-3">
            <label for="cnic" class="form-label">CNIC Number</label>
            <input type="text" class="form-control" id="cnic" name="cnic" value="{{ $tenant->cnic }}" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ $tenant->address }}" required>
        </div>
        <div class="mb-3">
            <label for="lease_date" class="form-label">Lease Date</label>
            <input type="text" class="form-control" id="lease_date" name="lease_date" value="{{ $tenant->lease_date }}" required>
        </div>
        <div class="mb-3">
            <label for="monthly_rent" class="form-label">Monthly Rent</label>
            <input type="number" class="form-control" id="monthly_rent" name="monthly_rent" step="0.01" value="{{ $tenant->monthly_rent }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Tenant</button>
    </form>
@endsection

@push('scripts')
    <script>
        flatpickr("#lease_date", {
            dateFormat: "Y-m-d",
        });
    </script>
@endpush
