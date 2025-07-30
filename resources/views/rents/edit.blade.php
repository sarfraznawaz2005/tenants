@extends('layouts.app')

@section('content')
    <h3>Edit Rent</h3>
    <div class="card">
        <div class="card-header">
            Rent Information
        </div>
        <div class="card-body">
            <form action="{{ route('rents.update', $rent->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="tenant_id" class="form-label">Tenant</label>
                    <select class="form-control @error('tenant_id') is-invalid @enderror" id="tenant_id" name="tenant_id" required>
                        <option value="">Select Tenant</option>
                        @foreach ($tenants as $tenant)
                            <option value="{{ $tenant->id }}" data-rent="{{ $tenant->monthly_rent }}" {{ old('tenant_id', $rent->tenant_id) == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                        @endforeach
                    </select>
                    @error('tenant_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="bill_id" class="form-label">Associated Bill (Optional)</label>
                    <select class="form-control @error('bill_id') is-invalid @enderror" id="bill_id" name="bill_id">
                        <option value="">Select Bill</option>
                        @foreach ($bills as $bill)
                            <option value="{{ $bill->id }}" {{ old('bill_id', $rent->bill_id) == $bill->id ? 'selected' : '' }} data-picture="{{ $bill->picture ? asset('storage/' . $bill->picture) : '' }}">PKR {{ $bill->amount }} on {{ \Carbon\Carbon::parse($bill->date)->format('D d M y') }}</option>
                        @endforeach
                    </select>
                    @error('bill_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                    <input type="number" class="form-control @error('amount_due') is-invalid @enderror" id="amount_due" name="amount_due" value="{{ old('amount_due', $rent->amount_due) }}">
                    @error('amount_due')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="amount_received" class="form-label">Amount Received</label>
                    <input type="number" class="form-control @error('amount_received') is-invalid @enderror" id="amount_received" name="amount_received" value="{{ old('amount_received', $rent->amount_received) }}" required>
                    @error('amount_received')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="text" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $rent->date) }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="due_date" class="form-label">Due Date</label>
                    <input type="text" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', $rent->due_date) }}">
                    @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#adjustmentModal">Add Adjustments</button>
                    <div id="adjustments-container" class="mb-3">
                        <!-- Adjustments will be listed here -->
                    </div>
                    <label class="form-label">Status</label>
                    <div class="form-check @error('status') is-invalid @enderror">
                        <input class="form-check-input" type="radio" name="status" id="paid" value="paid" {{ old('status', $rent->status) == 'paid' ? 'checked' : '' }}>
                        <label class="form-check-label" for="paid">Paid</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="not_paid" value="not_paid" {{ old('status', $rent->status) == 'not_paid' ? 'checked' : '' }}>
                        <label class="form-check-label" for="not_paid">Not Paid</label>
                    </div>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3" id="comment-section" style="{{ old('status', $rent->status) == 'not_paid' ? '' : 'display: none;' }}">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment">{{ old('comment', $rent->comment) }}</textarea>
                    @error('comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update Rent</button>
            </form>
        </div>
    </div>

    <!-- Adjustment Modal -->
    <div class="modal fade" id="adjustmentModal" tabindex="-1" aria-labelledby="adjustmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adjustmentModalLabel">Add Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="adjustment_name" class="form-label">Adjustment Name</label>
                        <input type="text" class="form-control" id="adjustment_name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="adjustment_type" id="adjustment_type_plus" value="plus" checked>
                            <label class="form-check-label" for="adjustment_type_plus">Plus</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="adjustment_type" id="adjustment_type_minus" value="minus">
                            <label class="form-check-label" for="adjustment_type_minus">Minus</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="adjustment_amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="adjustment_amount" step="0.01">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="add_adjustment_btn">Add</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let adjustments = @json($rent->adjustments ?? []);

        function renderAdjustments() {
            const container = $('#adjustments-container');
            container.empty();
            adjustments.forEach((adj, index) => {
                const adjustmentHtml = `
                    <div class="d-flex justify-content-between align-items-center border p-2 mb-2">
                        <span>${adj.name} (${adj.type === 'plus' ? '+' : '-'} PKR ${adj.amount})</span>
                        <button type="button" class="btn btn-danger btn-sm remove-adjustment" data-index="${index}">Remove</button>
                        <input type="hidden" name="adjustments[${index}][name]" value="${adj.name}">
                        <input type="hidden" name="adjustments[${index}][type]" value="${adj.type}">
                        <input type="hidden" name="adjustments[${index}][amount]" value="${adj.amount}">
                        ${adj.id ? `<input type="hidden" name="adjustments[${index}][id]" value="${adj.id}">` : ''}
                    </div>
                `;
                container.append(adjustmentHtml);
            });
        }

        $(document).ready(function() {
            renderAdjustments(); // Render existing adjustments on load

            flatpickr("#date", {
                dateFormat: "Y-m-d",
            });

            flatpickr("#due_date", {
                dateFormat: "Y-m-d",
            });

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

            // Adjustment Modal Logic
            $('#add_adjustment_btn').on('click', function() {
                const name = $('#adjustment_name').val();
                const type = $('input[name="adjustment_type"]:checked').val();
                const amount = parseFloat($('#adjustment_amount').val());

                if (name && !isNaN(amount) && amount > 0) {
                    adjustments.push({ name, type, amount });
                    renderAdjustments();
                    $('#adjustmentModal').modal('hide');
                    $('#adjustment_name').val('');
                    $('#adjustment_amount').val('');
                    $('#adjustment_type_plus').prop('checked', true);
                } else {
                    alert('Please enter a valid name and amount for the adjustment.');
                }
            });

            $('#adjustments-container').on('click', '.remove-adjustment', function() {
                const index = $(this).data('index');
                adjustments.splice(index, 1);
                renderAdjustments();
            });
        });
    </script>
@endpush
