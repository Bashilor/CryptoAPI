<?php

namespace App\Jobs;

use App\Payment;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPaymentNotification implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $payment;

    /**
     * SendPaymentNotification constructor.
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     *
     */
    public function handle()
    {
        $user = User::find($this->payment->user_id);

        $client = new Client();

        $client->request('POST', $user->website_url . '/' . $user->custom_uri, [
            'headers' => [
                'Webhook-Token' => $user->webhook_token
            ],
            'form_params' => [
                'uuid'    => $this->payment->uuid
            ]
        ]);
    }
}