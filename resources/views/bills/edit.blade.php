@extends('layouts.app')

@section('content')
    <h3>Edit Bill</h3>
    <div class="card">
        <div class="card-header">
            Bill Information
        </div>
        <div class="card-body">
            <form action="{{ route('bills.update', $bill->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="bill_type_id">Bill Type</label>
                    <select name="bill_type_id" id="bill_type_id" class="form-control @error('bill_type_id') is-invalid @enderror" required>
                        <option value="">Select Bill Type</option>
                        @foreach($billTypes as $billType)
                            <option value="{{ $billType->id }}" {{ old('bill_type_id', $bill->bill_type_id) == $billType->id ? 'selected' : '' }}>{{ $billType->name }}</option>
                        @endforeach
                    </select>
                    @error('bill_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="date">Date</label>
                    <input type="text" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', \Carbon\Carbon::parse($bill->date)->format('Y-m-d')) }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $bill->amount) }}" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="picture">Bill Picture</label>
                    <input type="file" name="picture" id="picture" class="form-control-file @error('picture') is-invalid @enderror">
                    @error('picture')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($bill->picture)
                        <img src="{{ asset('storage/' . $bill->picture) }}" alt="Bill Picture" width="100" class="mt-2">
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Update Bill</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        flatpickr("#date", {
            dateFormat: "Y-m-d",
        });
    </script>
@endpush
