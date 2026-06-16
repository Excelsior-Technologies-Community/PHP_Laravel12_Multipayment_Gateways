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