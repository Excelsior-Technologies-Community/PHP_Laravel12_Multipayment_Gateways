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

        <!-- Gateway Revenue Analytics -->

        <div class="row mb-4">

            <div class="col-md-4 mb-3">

                <div class="card shadow border-0">

                    <div class="card-body text-center">

                        <div style="font-size:40px;">💳</div>

                        <h5>
                            Stripe Revenue
                        </h5>

                        <h3>
                            ₹{{ number_format($stripeRevenue ?? 0, 2) }}
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-md-4 mb-3">

                <div class="card shadow border-0">

                    <div class="card-body text-center">

                        <div style="font-size:40px;">🅿️</div>

                        <h5>
                            PayPal Revenue
                        </h5>

                        <h3>
                            ₹{{ number_format($paypalRevenue ?? 0, 2) }}
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-md-4 mb-3">

                <div class="card shadow border-0">

                    <div class="card-body text-center">

                        <div style="font-size:40px;">🇮🇳</div>

                        <h5>
                            Razorpay Revenue
                        </h5>

                        <h3>
                            ₹{{ number_format($razorpayRevenue ?? 0, 2) }}
                        </h3>

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

                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#simulationModal">
                            ⚙️ Simulate
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- Simulation Modal -->
    <div class="modal fade" id="simulationModal" tabindex="-1" aria-labelledby="simulationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="simulationModalLabel">⚙️ Mock Gateway Failure Simulation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>ℹ️ Simulation Mode:</strong> Test different payment scenarios by selecting a specific outcome.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Enable Simulation</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enableSimulation" name="enable_simulation">
                            <label class="form-check-label" for="enableSimulation">Activate Mock Simulation</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Simulation Status</label>
                        <select class="form-select" id="simulationStatus" name="simulation_status" disabled>
                            <option value="random">🎲 Random (Default)</option>
                            <option value="Success">✅ Success</option>
                            <option value="Pending">⏳ Pending</option>
                            <option value="Failed">❌ Failed</option>
                        </select>
                        <small class="text-muted">Select a specific status to force that outcome</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Simulation Timing</label>
                        <select class="form-select" id="simulationTiming" name="simulation_timing" disabled>
                            <option value="instant">⚡ Instant (No Delay)</option>
                            <option value="fast">🚀 Fast (1-2 seconds)</option>
                            <option value="normal">🕐 Normal (3-5 seconds)</option>
                            <option value="slow">🐌 Slow (5-10 seconds)</option>
                        </select>
                        <small class="text-muted">Simulate network latency</small>
                    </div>

                    <div class="alert alert-warning mt-3" id="simulationWarning" style="display: none;">
                        <strong>⚠️ Warning:</strong> Simulation mode will override normal payment processing. Use for testing only.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="applySimulation()">Apply Simulation</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('enableSimulation').addEventListener('change', function() {
            const isEnabled = this.checked;
            document.getElementById('simulationStatus').disabled = !isEnabled;
            document.getElementById('simulationTiming').disabled = !isEnabled;
            document.getElementById('simulationWarning').style.display = isEnabled ? 'block' : 'none';
        });

        function applySimulation() {
            const enableSimulation = document.getElementById('enableSimulation').checked;
            const simulationStatus = document.getElementById('simulationStatus').value;
            const simulationTiming = document.getElementById('simulationTiming').value;

            const form = document.querySelector('form[action="{{ route('payment.process') }}"]');

            let simulationInput = document.createElement('input');
            simulationInput.type = 'hidden';
            simulationInput.name = 'enable_simulation';
            simulationInput.value = enableSimulation ? '1' : '0';
            form.appendChild(simulationInput);

            let statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'simulation_status';
            statusInput.value = simulationStatus;
            form.appendChild(statusInput);

            let timingInput = document.createElement('input');
            timingInput.type = 'hidden';
            timingInput.name = 'simulation_timing';
            timingInput.value = simulationTiming;
            form.appendChild(timingInput);

            const modal = bootstrap.Modal.getInstance(document.getElementById('simulationModal'));
            modal.hide();

            if (enableSimulation && simulationTiming !== 'instant') {
                const delays = {
                    'fast': 1500,
                    'normal': 3500,
                    'slow': 7500
                };
                const delay = delays[simulationTiming] || 0;

                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Simulating...';

                setTimeout(() => {
                    form.submit();
                }, delay);
            } else {
                form.submit();
            }
        }
    </script>

</body>

</html>