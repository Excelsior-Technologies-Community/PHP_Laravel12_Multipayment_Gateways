<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Services\PaymentManager;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payments.index', [

            'totalPayments' => Payment::count(),

            'successfulPayments' => Payment::where(
                'status',
                'Success'
            )->count(),

            'totalAmount' => Payment::where(
                'status',
                'Success'
            )->sum('amount'),

            'stripeRevenue' => Payment::where(
                'gateway',
                'Stripe'
            )->where(
                'status',
                'Success'
            )->sum('amount'),

            'paypalRevenue' => Payment::where(
                'gateway',
                'PayPal'
            )->where(
                'status',
                'Success'
            )->sum('amount'),

            'razorpayRevenue' => Payment::where(
                'gateway',
                'Razorpay'
            )->where(
                'status',
                'Success'
            )->sum('amount'),

            'recentPayments' => Payment::orderBy('id', 'asc')
                ->take(5)
                ->get(),
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'gateway' => 'required'
        ]);

        $gateway = PaymentManager::gateway(
            $request->gateway
        );

        $response = $gateway->pay([
            'amount' => $request->amount
        ]);

        $statuses = [
            'Success',
            'Pending',
            'Failed'
        ];

        $response['status'] =
            $statuses[array_rand($statuses)];

        $payment = Payment::create([
            'gateway' => $response['gateway'],
            'transaction_id' => $response['transaction_id'],
            'amount' => $request->amount,
            'status' => $response['status']
        ]);

        return view(
            'payments.success',
            compact('response', 'payment')
        );
    }

    public function history(Request $request)
    {
        $search = $request->search;

        $payments = Payment::when($search, function ($query) use ($search) {

            $query->where('gateway', 'like', "%{$search}%")
                ->orWhere('transaction_id', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
        })
            ->orderBy('id', 'asc')
            ->paginate(4)
            ->withQueryString();

        return view(
            'payments.history',
            compact('payments', 'search')
        );
    }

    public function receipt($id)
    {
        $payment = Payment::findOrFail($id);

        $pdf = Pdf::loadView(
            'payments.receipt',
            compact('payment')
        );

        return $pdf->download(
            'receipt_' .
                $payment->transaction_id .
                '.pdf'
        );
    }
}
