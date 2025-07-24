@extends('layouts.app')

@section('content')
    <h3>Edit Bill Type</h3>
    <form action="{{ route('bill-types.update', $bill_type->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="name">Bill Type Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $bill_type->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Bill Type</button>
    </form>
@endsection