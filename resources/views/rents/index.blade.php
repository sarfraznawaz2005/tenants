@extends('layouts.app')

@section('content')
    <h3>Rents</h3>
    <a href="{{ route('rents.create') }}" class="btn btn-primary mb-3">Add Rent</a>

    <div class="table-responsive">
        <table id="rents-table" class="table table-striped table-sm">
            <thead>
            <tr>
                <th>Tenant</th>
                <th>Amount Due</th>
                <th>Amount Received</th>
                <th>Amount Remaining</th>
                <th>Date</th>
                <th>Status</th>
                <th>Comment</th>
                <th>Associated Bill</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($rents as $rent)
                <tr>
                    <td>{{ $rent->tenant->name }}</td>
                    <td>PKR {{ number_format($rent->amount_due, 2) }}</td>
                    <td>PKR {{ number_format($rent->amount_received, 2) }}</td>
                    <td>PKR {{ number_format($rent->amount_remaining, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($rent->date)->format('D d M y') }}</td>
                    <td>{{ $rent->status }}</td>
                    <td>{{ $rent->comment }}</td>
                    <td>
                        @if ($rent->bill)
                            PKR {{ number_format($rent->bill->amount, 2) }}
                            on {{ \Carbon\Carbon::parse($rent->bill->date)->format('D d M y') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('rents.show', $rent->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('rents.edit', $rent->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('rents.destroy', $rent->id) }}" method="POST"
                              style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#rents-table').DataTable();
        });
    </script>
@endpush
