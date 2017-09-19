<?php

namespace App\Events;

use App\Payment;

class PaymentSuccessful extends Event
{
    public $payment;

    /**
     * Create a new event instance.
     *
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}