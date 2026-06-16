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