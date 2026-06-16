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