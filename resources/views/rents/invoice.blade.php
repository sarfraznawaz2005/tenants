<!DOCTYPE html>
<html>
<head>
    <title>Invoice for Rent #{{ $rent->id }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media print {
            .invoice-box {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>
<div class="invoice-box">
    <table>
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            Invoice
                        </td>
                        <td>
                            Invoice #: {{ $rent->id }}<br>
                            Date: {{ \Carbon\Carbon::now()->format('D d M y, h:i A') }}<br>
                            @if ($rent->due_date)
                                Due Date: {{ \Carbon\Carbon::parse($rent->due_date)->format('D d M y') }}
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            Name: {{ $rent->tenant->name }} ({{ $rent->tenant->cnic }})
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>
                Item
            </td>
            <td>
                Amount
            </td>
        </tr>

        <tr class="item">
            <td>
                Rent for {{ $rentMonth->format('F Y') }}
            </td>
            <td>
                PKR {{ number_format($rent->amount_due, 2) }}
            </td>
        </tr>

        @php
            $totalAmount = $rent->amount_due;
        @endphp

        @if ($rent->adjustments->count() > 0)
            <tr class="heading">
                <td>
                    Adjustments
                </td>
                <td>
                    Amount
                </td>
            </tr>
            @foreach ($rent->adjustments as $adjustment)
                <tr class="item">
                    <td>
                        {{ $adjustment->name }}
                    </td>
                    <td>
                        @if ($adjustment->type === 'plus')
                            + PKR {{ number_format($adjustment->amount, 2) }}
                            @php $totalAmount += $adjustment->amount; @endphp
                        @else
                            - PKR {{ number_format($adjustment->amount, 2) }}
                            @php $totalAmount -= $adjustment->amount; @endphp
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif

        @if ($rent->bill)
            <tr class="item last">
                <td>
                    {{ $rent->bill->billType->name }}
                </td>
                <td>
                    PKR {{ number_format($rent->bill->amount, 2) }}
                </td>
            </tr>

            {{--
            @if ($rent->bill->picture)
                <tr class="item last">
                    <td>
                        Bill Picture
                    </td>
                    <td>
                        <img src="{{ asset('storage/' . $rent->bill->picture) }}"
                             style="max-width: 150px; height: auto;">
                    </td>
                </tr>
            @endif
            --}}

        @endif

        <tr class="total">
            <td></td>
            <td>
                Total: PKR {{ number_format($totalAmount + ($rent->bill ? $rent->bill->amount : 0), 2) }}
            </td>
            </tr>
        </table>
    </div>

    <script src="{{asset('js/html2canvas.min.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded.');
            const invoiceBox = document.querySelector('.invoice-box');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            document.getElementById('shareInvoice').addEventListener('click', function() {
                console.log('Share Invoice button clicked.');
                html2canvas(invoiceBox).then(function(canvas) {
                    canvas.toBlob(function(blob) {
                        const file = new File([blob], 'invoice-{{ $rent->id }}.png', { type: 'image/png' });
                        if (navigator.canShare && navigator.canShare({ files: [file] })) {
                            navigator.share({
                                files: [file],
                                title: 'Invoice for Rent #{{ $rent->id }}',
                                text: 'Here is your invoice for rent #{{ $rent->id }}.'
                            }).catch((error) => console.error('Error sharing:', error));
                        } else {
                            alert('Web Share API is not supported in your browser or cannot share this file type.');
                            console.warn('Web Share API not supported or cannot share this file type.');
                        }
                    }, 'image/png');
                });
            });

            document.getElementById('saveInvoice').addEventListener('click', function() {
                const saveButton = this;
                saveButton.disabled = true;
                saveButton.textContent = 'Saving...';

                console.log('Save Invoice button clicked.');
                html2canvas(invoiceBox).then(function(canvas) {
                    const imageData = canvas.toDataURL('image/png');
                    fetch('/rents/{{ $rent->id }}/save-invoice-image', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ image: imageData })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Invoice saved successfully!');
                            console.log('Invoice saved:', data.imageUrl);
                        } else {
                            alert('Failed to save invoice.');
                            console.error('Error saving invoice:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while saving the invoice.');
                    })
                    .finally(() => {
                        saveButton.disabled = false;
                        saveButton.textContent = 'Save Invoice';
                    });
                });
            });
        });
    </script>
    <div style="text-align: center; margin-top: 20px;">
        <button id="saveInvoice" style="padding: 10px 20px; margin-right: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">Save Invoice</button>
        <button id="shareInvoice" style="padding: 10px 20px; background-color: #008CBA; color: white; border: none; cursor: pointer;">Share Invoice</button>
    </div>
</body>
</html>
