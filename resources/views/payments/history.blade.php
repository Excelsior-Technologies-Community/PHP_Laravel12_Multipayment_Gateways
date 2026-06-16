<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fc;
            font-family: 'Segoe UI', sans-serif;
        }

        .header-box {
            background: linear-gradient(135deg, #4f46e5, #2563eb);
            color: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .history-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }

        .table th {
            white-space: nowrap;
        }

        .search-box {
            border-radius: 10px;
        }

        .badge-status {
            font-size: 13px;
            padding: 7px 12px;
        }
    </style>
</head>

<body>

    <div class="container py-4">

        <div class="header-box shadow">

            <h2>📊 Payment Transaction History</h2>

            <p class="mb-0">
                View all payment records and transaction details.
            </p>

        </div>

        <div class="card history-card shadow-lg">

            <div class="card-header bg-dark text-white">

                <div class="row align-items-center">

                    <div class="col-md-6">
                        <h4 class="mb-0">
                            Transactions
                        </h4>
                    </div>

                    <div class="col-md-6 text-end">

                        <a href="/" class="btn btn-success">
                            New Payment
                        </a>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-4">

                        <input type="text" id="searchInput" class="form-control search-box"
                            placeholder="Search Transaction...">

                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-hover table-bordered align-middle" id="paymentTable">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>
                                <th>Gateway</th>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($payments as $payment)

                                <tr>

                                    <td>
                                        {{ $payment->id }}
                                    </td>

                                    <td>

                                        @if($payment->gateway == 'Stripe')
                                            <span class="badge bg-primary">
                                                Stripe
                                            </span>

                                        @elseif($payment->gateway == 'PayPal')
                                            <span class="badge bg-info">
                                                PayPal
                                            </span>

                                        @else
                                            <span class="badge bg-warning text-dark">
                                                Razorpay
                                            </span>
                                        @endif

                                    </td>

                                    <td>
                                        {{ $payment->transaction_id }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($payment->amount, 2) }}
                                    </td>

                                    <td>
                                        <span class="badge bg-success badge-status">
                                            {{ $payment->status }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $payment->created_at->format('d M Y h:i A') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center">
                                        No Payment Records Found
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                    <div class="d-flex justify-content-between align-items-center mt-3">

                        <div>
                            Showing
                            {{ $payments->firstItem() ?? 0 }}
                            to
                            {{ $payments->lastItem() ?? 0 }}
                            of
                            {{ $payments->total() }}
                            entries
                        </div>

                        <div>
                            {{ $payments->links('pagination::bootstrap-5') }}
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <script>

        document.getElementById('searchInput')
            .addEventListener('keyup', function () {

                let value = this.value.toLowerCase();

                let rows = document.querySelectorAll('#paymentTable tbody tr');

                rows.forEach(row => {

                    row.style.display =
                        row.innerText.toLowerCase().includes(value)
                            ? ''
                            : 'none';
                });

            });

    </script>

</body>

</html>