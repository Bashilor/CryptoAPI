<?php

namespace App\Console\Commands;

use App\Cryptocurrency;
use App\Jobs\SendPaymentNotification;
use App\Payment;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class PaymentStatus extends Command
{
    private $wallet_username;
    private $wallet_password;
    private $wallet_ip_address;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:paymentstatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the status of the payment for a payment.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->wallet_username       = env('WALLET_USERNAME');
        $this->wallet_password       = env('WALLET_PASSWORD');
        $this->wallet_ip_address     = env('WALLET_IP_ADDRESS');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cryptocurrencies = Cryptocurrency::where('maintenance', false)->get();
        foreach ($cryptocurrencies as $cryptocurrency)
        {
            $payments = Payment::where([
                ['cryptocurrency_id', $cryptocurrency->id],
                ['status', 1]
            ])->get();

            $wallet_uri = 'http://'. $this->wallet_username .':'. $this->wallet_password .'@'. $this->wallet_ip_address .':'. $cryptocurrency->wallet_port .'/';
            $client = new Client($wallet_uri);

            foreach ($payments as $payment)
            {
                $client->call('getbalance', [$payment->uuid, $cryptocurrency->confirmations]);
                $balance = json_decode($client->output)->result;

                if ($balance == $payment->amount)
                {
                    $payment->status = 2;
                    $payment->save();

                    $paymentNotification = (new SendPaymentNotification($payment))->onQueue('payment');
                    $this->dispatch($paymentNotification);
                }
            }
        }
    }
}
