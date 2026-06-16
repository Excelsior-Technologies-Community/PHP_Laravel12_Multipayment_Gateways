<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f4f7fc;
            font-family:'Segoe UI',sans-serif;
        }

        .success-card{
            max-width:700px;
            margin:auto;
            border:none;
            border-radius:20px;
            overflow:hidden;
        }

        .success-header{
            background:linear-gradient(135deg,#10b981,#059669);
            color:white;
            text-align:center;
            padding:30px;
        }

        .success-icon{
            font-size:70px;
        }

        .detail-box{
            background:#f8fafc;
            border-radius:12px;
            padding:15px;
            margin-bottom:15px;
        }

        .btn-custom{
            border-radius:10px;
            padding:10px 20px;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <div class="card success-card shadow-lg">

        <div class="success-header">

            <div class="success-icon">
                ✅
            </div>

            <h2>Payment Successful</h2>

            <p class="mb-0">
                Your payment has been processed successfully.
            </p>

        </div>

        <div class="card-body p-4">

            <div class="detail-box">
                <strong>Gateway:</strong>
                {{ $response['gateway'] }}
            </div>

            <div class="detail-box">
                <strong>Transaction ID:</strong>
                {{ $response['transaction_id'] }}
            </div>

            <div class="detail-box">
                <strong>Status:</strong>

                <span class="badge bg-success">
                    {{ $response['status'] }}
                </span>
            </div>

            <div class="detail-box">
                <strong>Amount:</strong>
                ₹{{ request('amount') }}
            </div>

            <div class="text-center mt-4">

                <a href="/"
                   class="btn btn-primary btn-custom">
                    New Payment
                </a>

                <a href="{{ route('payment.history') }}"
                   class="btn btn-success btn-custom">
                    View History
                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>