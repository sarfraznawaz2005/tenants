@extends('layouts.app')

@section('content')
    <h3>Rent Details</h3>

    <div class="card">
        <div class="card-header">
            Rent Information
        </div>
        <div class="card-body">
            <p><strong>Tenant:</strong> {{ $rent->tenant->name }}</p>
            <p><strong>Amount Due:</strong> PKR {{ number_format($rent->amount_due, 2) }}</p>
            <p><strong>Amount Received:</strong> PKR {{ number_format($rent->amount_received, 2) }}</p>
            <p><strong>Amount Remaining:</strong> PKR {{ number_format($rent->amount_remaining, 2) }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($rent->date)->format('D d F Y') }}</p>
            <p><strong>Status:</strong> {{ $rent->status }}</p>
            <p><strong>Comment:</strong> {{ $rent->comment }}</p>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            Comments
        </div>
        <div class="card-body">
            @foreach ($rent->comments as $comment)
                <div class="mb-2">
                    <p class="mb-0">{{ $comment->body }}</p>
                    <small class="text-muted">Posted on {{ $comment->created_at->format('D d F Y, h:i A') }}</small>
                </div>
                <hr>
            @endforeach

            <form action="{{ route('comments.store', $rent) }}" method="POST" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label for="body" class="form-label">Add a comment</label>
                    <textarea class="form-control" id="body" name="body" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add Comment</button>
            </form>
        </div>
    </div>
@endsection
