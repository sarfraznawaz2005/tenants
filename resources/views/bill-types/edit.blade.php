@extends('layouts.app')

@section('content')
    <h3>Edit Bill Type</h3>
    <div class="card">
        <div class="card-header">
            Bill Type Information
        </div>
        <div class="card-body">
            <form action="{{ route('bill-types.update', $bill_type->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name">Bill Type Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $bill_type->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update Bill Type</button>
            </form>
        </div>
    </div>
@endsection