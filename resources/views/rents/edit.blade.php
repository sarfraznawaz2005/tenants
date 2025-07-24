@extends('layouts.app')

@section('content')
    <h3>Edit Rent</h3>
    <form action="{{ route('rents.update', $rent->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="tenant_id" class="form-label">Tenant</label>
            <select class="form-control" id="tenant_id" name="tenant_id" required>
                <option value="">Select Tenant</option>
                @foreach ($tenants as $tenant)
                    <option value="{{ $tenant->id }}" data-rent="{{ $tenant->monthly_rent }}" {{ $rent->tenant_id == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="bill_id" class="form-label">Associated Bill (Optional)</label>
            <select class="form-control" id="bill_id" name="bill_id">
                <option value="">Select Bill</option>
                @foreach ($bills as $bill)
                    <option value="{{ $bill->id }}" {{ $rent->bill_id == $bill->id ? 'selected' : '' }} data-picture="{{ $bill->picture ? asset('storage/' . $bill->picture) : '' }}">PKR {{ $bill->amount }} on {{ \Carbon\Carbon::parse($bill->date)->format('D d M y') }}</option>
                @endforeach
            </select>
            <div id="bill-image-preview" class="mt-2" style="display: none;">
                <img id="bill-image" src="" alt="Bill Image" style="max-width: 200px; cursor: pointer;">
            </div>
        </div>
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
        <div class="mb-3">
            <label for="amount_due" class="form-label">Amount Due</label>
            <input type="number" class="form-control" id="amount_due" name="amount_due" value="{{ $rent->amount_due }}" readonly>
        </div>
        <div class="mb-3">
            <label for="amount_received" class="form-label">Amount Received</label>
            <input type="number" class="form-control" id="amount_received" name="amount_received" value="{{ $rent->amount_received }}" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="text" class="form-control" id="date" name="date" value="{{ $rent->date }}" required>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="text" class="form-control" id="due_date" name="due_date" value="{{ $rent->due_date }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="paid" value="paid" {{ $rent->status == 'paid' ? 'checked' : '' }}>
                <label class="form-check-label" for="paid">Paid</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="not_paid" value="not_paid" {{ $rent->status == 'not_paid' ? 'checked' : '' }}>
                <label class="form-check-label" for="not_paid">Not Paid</label>
            </div>
        </div>
        <div class="mb-3" id="comment-section" style="{{ $rent->status == 'not_paid' ? '' : 'display: none;' }}">
            <label for="comment" class="form-label">Comment</label>
            <textarea class="form-control" id="comment" name="comment">{{ $rent->comment }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Rent</button>
    </form>
@endsection

@push('scripts')
    <script>
        flatpickr("#date", {
            dateFormat: "Y-m-d",
        });

        flatpickr("#due_date", {
            dateFormat: "Y-m-d",
        });

        $(document).ready(function() {
            $('#tenant_id').on('change', function() {
                const rent = $(this).find(':selected').data('rent');
                $('#amount_due').val(rent);
            });

            $('input[name="status"]').on('change', function() {
                if (this.value === 'not_paid') {
                    $('#comment-section').show();
                } else {
                    $('#comment-section').hide();
                }
            });

            $('#bill_id').on('change', function() {
                const picture = $(this).find(':selected').data('picture');
                if (picture) {
                    $('#bill-image').attr('src', picture);
                    $('#bill-image-preview').show();
                } else {
                    $('#bill-image-preview').hide();
                }
            });

            $('#bill-image').on('click', function() {
                $('#modal-image').attr('src', $(this).attr('src'));
                const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
                imageModal.show();
            });

            // Initial check for edit form
            const initialPicture = $('#bill_id').find(':selected').data('picture');
            if (initialPicture) {
                $('#bill-image').attr('src', initialPicture);
                $('#bill-image-preview').show();
            }
        });
    </script>
@endpush
