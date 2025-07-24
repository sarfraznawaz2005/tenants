@extends('layouts.app')

@section('content')
    <h3>Rent Details <a href="{{ route('rents.invoice', $rent->id) }}" class="btn btn-primary btn-sm ms-2" target="_blank">Invoice</a></h3>

    <div class="card">
        <div class="card-header">
            Rent Information
        </div>
        <div class="card-body">
            <p><strong>Tenant:</strong> {{ $rent->tenant->name }}</p>
            <p><strong>Amount Due:</strong> PKR {{ number_format($rent->amount_due, 2) }}</p>
            <p><strong>Amount Received:</strong> PKR {{ number_format($rent->amount_received, 2) }}</p>
            <p><strong>Amount Remaining:</strong> PKR {{ number_format($rent->amount_remaining, 2) }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($rent->date)->format('D d M y') }}</p>
            <p><strong>Status:</strong> {{ $rent->status }}</p>
            <p><strong>Comment:</strong> {{ $rent->comment }}</p>
        </div>
    </div>

    @if ($rent->bill)
        <div class="card mt-4">
            <div class="card-header">
                Associated Bill Details
            </div>
            <div class="card-body">
                <p><strong>Bill Type:</strong> {{ $rent->bill->billType->name }}</p>
                <p><strong>Amount:</strong> PKR {{ number_format($rent->bill->amount, 2) }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($rent->bill->date)->format('D d M y') }}</p>
                @if ($rent->bill->picture)
                    <p><strong>Picture:</strong></p>
                    <img src="{{ asset('storage/' . $rent->bill->picture) }}" alt="Bill Picture" style="max-width: 200px; cursor: pointer;" id="bill-image-show">
                @else
                    <p><strong>Picture:</strong> N/A</p>
                @endif
            </div>
        </div>
    @endif

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Bill Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modal-image" src="" class="img-fluid" alt="Bill Image">
                </div>
            </div>
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
                    <small class="text-muted">Posted on {{ $comment->created_at->format('D d M y, h:i A') }}</small>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#bill-image-show').on('click', function() {
                $('#modal-image').attr('src', $(this).attr('src'));
                const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
                imageModal.show();
            });
        });
    </script>
@endpush
