@extends('layouts.app')

@section('content')
    <h3>Tenants</h3>
    <a href="{{ route('tenants.create') }}" class="btn btn-primary mb-3">Add Tenant</a>
    <table id="tenants-table" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>CNIC</th>
                <th>Address</th>
                <th>Lease Date</th>
                <th>Monthly Rent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tenants as $tenant)
                <tr>
                    <td>{{ $tenant->name }}</td>
                    <td>{{ $tenant->phone }}</td>
                    <td>{{ $tenant->cnic }}</td>
                    <td>{{ $tenant->address }}</td>
                    <td>{{ \Carbon\Carbon::parse($tenant->lease_date)->format('D d M y') }}</td>
                    <td>PKR {{ number_format($tenant->monthly_rent, 2) }}</td>
                    <td>
                        <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tenants-table').DataTable();
        });
    </script>
@endpush
