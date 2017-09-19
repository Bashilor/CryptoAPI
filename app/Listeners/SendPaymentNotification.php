<?php

namespace App\Listeners;

use App\Events\PaymentSuccessful;
use App\User;
use GuzzleHttp\Client;

class SendPaymentNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param PaymentSuccessful $event
     * @return void
     */
    public function handle(PaymentSuccessful $event)
    {
        $client = new Client();

        $client->post('http://localhost:8000/api/v1/get', [
            'headers' => [
                'Webhook-Token' => User::where('id', $event->payment->user_id)->first()->webhook_token
            ],
            'form_params' => [
                'uuid'    => $event->payment->uuid
            ]
        ]);
    }
}