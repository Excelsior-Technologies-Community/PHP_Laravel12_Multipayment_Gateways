# PHP_Laravel12_Multipayment_Gateways


## Project Overview

Laravel 12 Multi Payment Gateway System is a payment management application that demonstrates multiple payment gateway integration using the Strategy Design Pattern.

The system allows users to select different payment gateways such as Stripe, PayPal, and Razorpay, process payments, and manage transaction history through a modern dashboard interface.


## Features

- Stripe Gateway Integration (Demo)
- PayPal Gateway Integration (Demo)
- Razorpay Gateway Integration (Demo)
- Strategy Design Pattern
- Dashboard Statistics
- Payment Processing
- Transaction History
- Search Transactions
- Pagination Support
- Responsive Bootstrap 5 UI



## Technologies Used

- Laravel 12
- PHP
- MySQL
- Bootstrap 5
- HTML
- CSS


## Application Flow

1. Enter Amount
2. Select Payment Gateway
3. Click Pay Now
4. Payment is Processed
5. Transaction Saved in Database
6. Success Page Displayed
7. View Transaction History

---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Multipayment_Gateways "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Multipayment_Gateways

```

#### Explanation:

Creates a new Laravel 12 application and sets up the default project structure. 

This serves as the foundation for building the multi-payment gateway system.




## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_multipayment_gateways
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_multipayment_gateways


```

### Then Run:

```
php artisan migrate

```


#### Explanation:

Configures the MySQL database connection for the application. 

Laravel uses this database to store payment transaction records and application data.





## STEP 3: Create Contracts and Gateway Services

### Create folders:

```
mkdir app/Contracts
mkdir app/Services
mkdir app/Services/Gateways

```
 
#### Explanation:

Creates the contract and gateway service classes used to implement the Strategy Design Pattern for payment processing.




### Create Interface

#### app/Contracts/PaymentGatewayInterface.php

```
<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    public function pay(array $data);
}

```


### Create Stripe Gateway

#### app/Services/Gateways/StripeGateway.php

```
<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;

class StripeGateway implements PaymentGatewayInterface
{
    public function pay(array $data)
    {
        return [
            'gateway' => 'Stripe',
            'transaction_id' => 'STR'.rand(10000,99999),
            'status' => 'Success'
        ];
    }
}

```


### Create Paypal Gateway

#### app/Services/Gateways/PaypalGateway.php

```
<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;

class PaypalGateway implements PaymentGatewayInterface
{
    public function pay(array $data)
    {
        return [
            'gateway' => 'PayPal',
            'transaction_id' => 'PAY'.rand(10000,99999),
            'status' => 'Success'
        ];
    }
}

```


### Create Razorpay Gateway

#### app/Services/Gateways/RazorpayGateway.php

```
<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;

class RazorpayGateway implements PaymentGatewayInterface
{
    public function pay(array $data)
    {
        return [
            'gateway' => 'Razorpay',
            'transaction_id' => 'RAZ'.rand(10000,99999),
            'status' => 'Success'
        ];
    }
}

```


### Create Payment Manager

#### app/Services/PaymentManager.php

```
<?php

namespace App\Services;

use App\Services\Gateways\StripeGateway;
use App\Services\Gateways\PaypalGateway;
use App\Services\Gateways\RazorpayGateway;

class PaymentManager
{
    public static function gateway($gateway)
    {
        return match($gateway)
        {
            'stripe' => new StripeGateway(),
            'paypal' => new PaypalGateway(),
            'razorpay' => new RazorpayGateway(),
            default => throw new \Exception('Invalid Gateway')
        };
    }
}

```

#### Explanation: 

Implements the Strategy Design Pattern by creating a common payment interface and separate gateway service classes. 

This allows switching between different payment gateways without changing business logic.




## STEP 4: Create Payment Table & Model

### Run:

```
php artisan make:model Payment -m

```

### database/migrations/xxxx_create_payments_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function ($table) {

            $table->id();

            $table->string('gateway');

            $table->string('transaction_id');

            $table->decimal('amount', 10, 2);

            $table->string('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

```

### app/Models/Payment.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'gateway',
        'transaction_id',
        'amount',
        'status',
    ];
}

```


### Then Run:

```
php artisan migrate

```


#### Explanation: 

Creates the payments table and corresponding Eloquent model. 

The table stores payment transactions including gateway name, amount, transaction ID, and status.




## STEP 5: Create Controller

### Run:

```
php artisan make:controller PaymentController

```

### app/Http/Controllers/PaymentController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Services\PaymentManager;

class PaymentController extends Controller
{
    public function index()
{
    return view('payments.index', [
        'totalPayments' => \App\Models\Payment::count(),
        'successfulPayments' => \App\Models\Payment::where('status', 'Success')->count(),
        'totalAmount' => \App\Models\Payment::sum('amount'),
        'recentPayments' => \App\Models\Payment::latest()->take(5)->get(),
    ]);
}

    public function process(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'gateway' => 'required'
        ]);

        $gateway = PaymentManager::gateway($request->gateway);

        $response = $gateway->pay([
            'amount' => $request->amount
        ]);

        Payment::create([
            'gateway' => $response['gateway'],
            'transaction_id' => $response['transaction_id'],
            'amount' => $request->amount,
            'status' => $response['status']
        ]);

        return view('payments.success', compact('response'));
    }

    

    public function history()
    {
         $payments = Payment::oldest()->paginate(4);

        return view('payments.history', compact('payments'));
    }
}

```

#### Explanation: 

Handles payment requests, gateway selection, transaction processing, dashboard statistics, and payment history management.





## STEP 6: Add Routes

### routes/web.php

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', [PaymentController::class, 'index']);

Route::post('/payment-process', [PaymentController::class, 'process'])
    ->name('payment.process');

Route::get('/payment-history', [PaymentController::class, 'history'])
    ->name('payment.history');

```

#### Explanation: 

Defines application URLs and maps them to controller methods. 

These routes handle payment processing, dashboard access, and transaction history.




## STEP 7: Create Blade Files

### resources/views/payments/index.blade.php

```
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

```



### resources/views/payments/success.blade.php


```
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

```

### resources/views/payments/history.blade.php

```
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

```

#### Explanation: 

Provides the main user interface for entering payment details, selecting a gateway, and viewing payment statistics.

Uses Bootstrap 5 components and custom styling to create a responsive and modern user interface across all pages.






## STEP 8: Run the App  

### Start dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000

```

#### Explanation:

Starts the Laravel development server and allows testing of payment processing, transaction storage, and dashboard functionality.



## Expected Output:


### Dashboard Overview


<img width="1884" height="953" alt="Screenshot 2026-06-16 155159" src="https://github.com/user-attachments/assets/a623d046-2526-4831-b5eb-40d56ada036f" />


### Payment Processing


<img width="1879" height="950" alt="Screenshot 2026-06-16 155217" src="https://github.com/user-attachments/assets/14bf6c89-568c-443b-9f59-7e85906d794e" />


### Payment Success Confirmation


<img width="1897" height="953" alt="Screenshot 2026-06-16 155242" src="https://github.com/user-attachments/assets/a6a96552-a697-4099-904c-2a4b8bee98e7" />


### Transaction History Management


<img width="1905" height="949" alt="Screenshot 2026-06-16 155253" src="https://github.com/user-attachments/assets/e32d4bfa-0df6-4fea-938e-7bb1933b3d02" />


###  Transaction Search & Filtering


<img width="1909" height="946" alt="Screenshot 2026-06-16 155359" src="https://github.com/user-attachments/assets/9efbb5cf-09ff-4572-940c-3116ab0178c1" />

<img width="1903" height="941" alt="Screenshot 2026-06-16 155427" src="https://github.com/user-attachments/assets/48e26838-bf4b-48da-ac0e-1bbb8b63d7a0" />

<img width="1893" height="947" alt="Screenshot 2026-06-16 155600" src="https://github.com/user-attachments/assets/880156ea-2646-4177-a10e-ed9d70e4096d" />


### Transaction Pagination & Navigation


<img width="1906" height="955" alt="Screenshot 2026-06-16 155304" src="https://github.com/user-attachments/assets/68e6fda1-38f4-4ec2-a59c-3323081a6bde" />

---


## Project Folder Structure:

```
PHP_Laravel12_Multipayment_Gateways
│
├── app
│   │
│   ├── Contracts
│   │   └── PaymentGatewayInterface.php
│   │
│   ├── Http
│   │   └── Controllers
│   │       └── PaymentController.php
│   │
│   ├── Models
│   │   └── Payment.php
│   │
│   └── Services
│       │
│       ├── PaymentManager.php
│       │
│       └── Gateways
│           ├── StripeGateway.php
│           ├── PaypalGateway.php
│           └── RazorpayGateway.php
│
├── bootstrap
│
├── config
│
├── database
│   │
│   ├── factories
│   ├── seeders
│   │
│   └── migrations
│       └── xxxx_xx_xx_create_payments_table.php
│
├── public
│
├── resources
│   │
│   └── views
│       │
│       └── payments
│           ├── index.blade.php
│           ├── success.blade.php
│           └── history.blade.php
│
├── routes
│   └── web.php
│
├── storage
│
├── tests
│
├── .env
├── artisan
├── composer.json
├── package.json
└── README.md
```

