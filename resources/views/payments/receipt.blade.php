<!DOCTYPE html>
<html>

<head>

    <title>
        Official Payment Receipt
    </title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .receipt-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .receipt-header {
            background: linear-gradient(135deg, #4f46e5, #2563eb);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .receipt-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .receipt-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }

        .receipt-body {
            padding: 30px;
        }

        .receipt-id {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px dashed #e5e7eb;
        }

        .receipt-details {
            margin-bottom: 25px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-label {
            font-weight: 600;
            color: #374151;
        }

        .detail-value {
            color: #6b7280;
            font-weight: 500;
        }

        .amount-row {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }

        .amount-row .amount {
            font-size: 36px;
            font-weight: 700;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .status-success {
            background: #d1fae5;
            color: #065f46;
        }

        .status-failed {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .receipt-footer {
            background: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
        }

        .gateway-icon {
            font-size: 24px;
            margin-right: 8px;
        }
    </style>

</head>

<body>

    <div class="receipt-container">

        <div class="receipt-header">
            <h1>🧾 Official Payment Receipt</h1>
            <p>Thank you for your payment</p>
        </div>

        <div class="receipt-body">

            <div class="receipt-id">
                Receipt ID: #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}
            </div>

            <div class="receipt-details">

                <div class="detail-row">
                    <span class="detail-label">Transaction ID</span>
                    <span class="detail-value">{{ $payment->transaction_id }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Payment Gateway</span>
                    <span class="detail-value">
                        @if($payment->gateway == 'Stripe')
                        <span class="gateway-icon">💳</span> Stripe
                        @elseif($payment->gateway == 'PayPal')
                        <span class="gateway-icon">🅿️</span> PayPal
                        @else
                        <span class="gateway-icon">🇮🇳</span> Razorpay
                        @endif
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Payment Date</span>
                    <span class="detail-value">{{ $payment->created_at->format('d M Y h:i A') }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Payment Status</span>
                    <span class="detail-value">
                        @if($payment->status == 'Success')
                        <span class="status-badge status-success">✓ Success</span>
                        @elseif($payment->status == 'Pending')
                        <span class="status-badge status-pending">⏳ Pending</span>
                        @else
                        <span class="status-badge status-failed">✗ Failed</span>
                        @endif
                    </span>
                </div>

            </div>

            <div class="amount-row">
                <div style="font-size: 14px; margin-bottom: 5px;">Total Amount Paid</div>
                <div class="amount">₹{{ number_format($payment->amount, 2) }}</div>
            </div>

        </div>

        <div class="receipt-footer">
            <p>This is an automatically generated receipt. For any queries, please contact support.</p>
            <p>Generated on: {{ now()->format('d M Y h:i A') }}</p>
        </div>

    </div>

</body>

</html>