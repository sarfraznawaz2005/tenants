@extends('layouts.app')

@section('content')
    <h3>Bills</h3>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="bills-tab" data-bs-toggle="tab" data-bs-target="#bills" type="button" role="tab" aria-controls="bills" aria-selected="true">Bills</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="bill-types-tab" data-bs-toggle="tab" data-bs-target="#bill-types" type="button" role="tab" aria-controls="bill-types" aria-selected="false">Bill Types</button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="bills" role="tabpanel" aria-labelledby="bills-tab">
            <div class="mt-3 mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBillModal">
                    Add Bill
                </button>
            </div>
            <div class="card">
                <div class="card-header">Bills</div>
                <div class="card-body">
                    <table class="table table-bordered" id="billsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bill Type</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Picture</th>
                                <th class="actions-column">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bills as $bill)
                                <tr>
                                    <td>{{ $bill->id }}</td>
                                    <td>{{ $bill->billType->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($bill->date)->format('D d M y') }}</td>
                                    <td>{{ $bill->amount }}</td>
                                    <td>
                                        @if($bill->picture)
                                            <img src="{{ asset('storage/' . $bill->picture) }}" alt="Bill Picture" width="100">
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="actions-column">
                                        <a href="{{ route('bills.edit', $bill->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('bills.destroy', $bill->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this bill?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="bill-types" role="tabpanel" aria-labelledby="bill-types-tab">
            <div class="mt-3 mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBillTypeModal">
                    Add Bill Type
                </button>
            </div>
            <div class="card">
                <div class="card-header">Bill Types</div>
                <div class="card-body">
                    <table class="table table-bordered" id="billTypesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th class="actions-column">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($billTypes as $billType)
                                <tr>
                                    <td>{{ $billType->id }}</td>
                                    <td>{{ $billType->name }}</td>
                                    <td class="actions-column">
                                        <a href="{{ route('bill-types.edit', $billType->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('bill-types.destroy', $billType->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this bill type?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bill Modal -->
    <div class="modal fade" id="addBillModal" tabindex="-1" aria-labelledby="addBillModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBillModalLabel">Add Bill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('bills.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="bill_type_id">Bill Type</label>
                            <select name="bill_type_id" id="bill_type_id" class="form-control @error('bill_type_id') is-invalid @enderror" required>
                                <option value="">Select Bill Type</option>
                                @foreach($billTypes as $billType)
                                    <option value="{{ $billType->id }}" {{ old('bill_type_id') == $billType->id ? 'selected' : '' }}>{{ $billType->name }}</option>
                                @endforeach
                            </select>
                            @error('bill_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="date">Date</label>
                            <input type="text" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
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
                        </div>
                        <button type="submit" class="btn btn-primary">Add Bill</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bill Type Modal -->
    <div class="modal fade" id="addBillTypeModal" tabindex="-1" aria-labelledby="addBillTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBillTypeModalLabel">Add Bill Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('bills.storeType') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name">Bill Type Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Add Bill Type</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        flatpickr("#date", {
            dateFormat: "Y-m-d",
        });

        $(document).ready(function() {
            $('#billsTable').DataTable();
            $('#billTypesTable').DataTable();
        });
    </script>
@endpush