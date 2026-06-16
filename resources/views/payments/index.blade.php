<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel 12 Multi Payment Gateway</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fc;
        }

        .header-card {
            background: linear-gradient(135deg, #4f46e5, #2563eb);
            color: white;
            border-radius: 15px;
        }

        .stats-card {
            border: none;
            border-radius: 15px;
            transition: 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .payment-card {
            border: none;
            border-radius: 15px;
        }

        .btn-pay {
            background: #2563eb;
            color: white;
            font-weight: 600;
        }

        .btn-pay:hover {
            background: #1d4ed8;
            color: white;
        }

        .gateway-icon {
            font-size: 18px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="container py-5">

        <!-- Header -->

        <div class="header-card p-4 mb-4 shadow">
            <h2>💳 Laravel 12 Multi Payment Gateway System</h2>
            <p class="mb-0">
                Manage payments using Stripe, PayPal and Razorpay.
            </p>
        </div>

        <!-- Statistics -->

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card stats-card shadow bg-primary text-white">
                    <div class="card-body text-center">
                        <div style="font-size:40px;">💳</div>
                        <h5>Total Payments</h5>
                        <h2>{{ $totalPayments ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card stats-card shadow bg-success text-white">
                    <div class="card-body text-center">
                        <div style="font-size:40px;">✅</div>
                        <h5>Successful Payments</h5>
                        <h2>{{ $successfulPayments ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card stats-card shadow bg-warning">
                    <div class="card-body text-center">
                        <div style="font-size:40px;">💰</div>
                        <h5>Total Revenue</h5>
                        <h2>₹{{ $totalAmount ?? 0 }}</h2>
                    </div>
                </div>
            </div>

        </div>

        <!-- Payment Form -->

        <div class="card payment-card shadow-lg">

            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">
                    Make Payment
                </h4>
            </div>

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">

                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif

                <form action="{{ route('payment.process') }}" method="POST">

                    @csrf

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Enter Amount
                        </label>

                        <input type="number" name="amount" class="form-control form-control-lg"
                            placeholder="Enter Amount" required>

                    </div>

                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            Select Payment Gateway
                        </label>

                        <select name="gateway" class="form-select form-select-lg" required>

                            <option value="">
                                -- Choose Gateway --
                            </option>

                            <option value="stripe">
                                💳 Stripe
                            </option>

                            <option value="paypal">
                                🅿️ PayPal
                            </option>

                            <option value="razorpay">
                                🇮🇳 Razorpay
                            </option>

                        </select>

                    </div>

                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-pay flex-fill">

                            Pay Now

                        </button>

                        <a href="{{ route('payment.history') }}" class="btn btn-success">

                            View History

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</body>

</html>