@extends('layouts.app')

@section('content')
    <h3>Edit Bill</h3>
    <form action="{{ route('bills.update', $bill->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="bill_type_id">Bill Type</label>
            <select name="bill_type_id" id="bill_type_id" class="form-control" required>
                <option value="">Select Bill Type</option>
                @foreach($billTypes as $billType)
                    <option value="{{ $billType->id }}" {{ $bill->bill_type_id == $billType->id ? 'selected' : '' }}>{{ $billType->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="date">Date</label>
            <input type="text" name="date" id="date" class="form-control" value="{{ \Carbon\Carbon::parse($bill->date)->format('D d M y') }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" value="{{ $bill->amount }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="picture">Bill Picture</label>
            <input type="file" name="picture" id="picture" class="form-control-file">
            @if($bill->picture)
                <img src="{{ asset('storage/' . $bill->picture) }}" alt="Bill Picture" width="100" class="mt-2">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Update Bill</button>
    </form>
@endsection

@push('scripts')
    <script>
        flatpickr("#date", {
            dateFormat: "Y-m-d",
        });
    </script>
@endpush
