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