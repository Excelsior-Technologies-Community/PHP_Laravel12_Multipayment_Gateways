<!DOCTYPE html>
<html>

<head>

    <title>
        Payment Receipt
    </title>

    <style>
        body {
            font-family: Arial;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 12px;
        }
    </style>

</head>

<body>

    <h2>
        Payment Receipt
    </h2>

    <table>

        <tr>
            <th>Transaction ID</th>
            <td>
                {{ $payment->transaction_id }}
            </td>
        </tr>

        <tr>
            <th>Gateway</th>
            <td>
                {{ $payment->gateway }}
            </td>
        </tr>

        <tr>
            <th>Amount</th>
            <td>
                ₹{{ number_format($payment->amount,2) }}
            </td>
        </tr>

        <tr>
            <th>Status</th>
            <td>
                {{ $payment->status }}
            </td>
        </tr>

        <tr>
            <th>Date</th>
            <td>
                {{ $payment->created_at->format('d M Y h:i A') }}
            </td>
        </tr>

    </table>

</body>

</html>