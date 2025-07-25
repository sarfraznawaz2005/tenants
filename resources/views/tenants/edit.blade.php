@extends('layouts.app')

@section('content')
    <h3>Edit Tenant</h3>
    <div class="card">
        <div class="card-header">
            Tenant Information
        </div>
        <div class="card-body">
            <form action="{{ route('tenants.update', $tenant->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $tenant->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $tenant->phone) }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="cnic" class="form-label">CNIC Number</label>
                    <input type="text" class="form-control @error('cnic') is-invalid @enderror" id="cnic" name="cnic" value="{{ old('cnic', $tenant->cnic) }}" required>
                    @error('cnic')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $tenant->address) }}" required>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="lease_date" class="form-label">Lease Date</label>
                    <input type="text" class="form-control @error('lease_date') is-invalid @enderror" id="lease_date" name="lease_date" value="{{ old('lease_date', $tenant->lease_date) }}" required>
                    @error('lease_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="monthly_rent" class="form-label">Monthly Rent</label>
                    <input type="number" class="form-control @error('monthly_rent') is-invalid @enderror" id="monthly_rent" name="monthly_rent" value="{{ old('monthly_rent', $tenant->monthly_rent) }}" required>
                    @error('monthly_rent')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update Tenant</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        flatpickr("#lease_date", {
            dateFormat: "D d M y",
        });
    </script>
@endpush
