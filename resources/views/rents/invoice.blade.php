<!DOCTYPE html>
<html>
<head>
    <title>Invoice for Rent #{{ $rent->id }}</title>
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
        .invoice-box table tr.item td{
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
                                Tenant Name: {{ $rent->tenant->name }}<br>
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
                    PKR {{ number_format($rent->amount_remaining, 2) }}
                </td>
            </tr>

            @if ($rent->bill)
            <tr class="item last">
                <td>
                    {{ $rent->bill->billType->name }}
                </td>
                <td>
                    PKR {{ number_format($rent->bill->amount, 2) }}
                </td>
            </tr>
            @endif

            <tr class="total">
                <td></td>
                <td>
                   Total: PKR {{ number_format($rent->amount_remaining + ($rent->bill ? $rent->bill->amount : 0), 2) }}
                </td>
            </tr>
        </table>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>