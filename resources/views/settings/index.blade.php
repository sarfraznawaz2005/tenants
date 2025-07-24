@extends('layouts.app')

@section('content')
    <h3>Settings</h3>
    <p>This will delete all tenants and rents from the database.</p>
    <form action="{{ route('settings.clean') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete all data?')">Clean DB</button>
    </form>
@endsection