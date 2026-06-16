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