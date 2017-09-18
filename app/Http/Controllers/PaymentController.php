<?php

namespace App\Http\Controllers;

use App\Cryptocurrency;
use App\Payment;
use App\Jobs\NewAPICall;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JsonRpc\Client;
use Webpatser\Uuid\Uuid;

class PaymentController extends Controller
{
    private $wallet_username;
    private $wallet_password;
    private $wallet_ip_address;

    /**
     * Create a new controller instance.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->dispatch(new NewAPICall($request->header('Api-Token'), $request->path(), Carbon::now()));

        $this->wallet_username       = env('WALLET_USERNAME');
        $this->wallet_password       = env('WALLET_PASSWORD');
        $this->wallet_ip_address     = env('WALLET_IP_ADDRESS');
    }

    /**
     * @param $cryptocurrency
     * @param $uuid
     * @return mixed
     */
    public function getNewAddress($cryptocurrency, $uuid)
    {
        $wallet_uri = 'http://'. $this->wallet_username .':'. $this->wallet_password .'@'. $this->wallet_ip_address .':'. $cryptocurrency->wallet_port .'/';

        $client = new Client($wallet_uri);

        $client->call('getnewaddress', [$uuid]);
        $newAddress = json_decode($client->output)->result;

        return $newAddress;
    }

    /**
     * @usage : /payment/new
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $body = json_decode($request->getContent());

        Validator::make($request->json()->all(), [
            'cryptocurrency' => 'required|string',
            'amount' => 'required|numeric'
        ])->validate();

        $cryptocurrency = Cryptocurrency::where([
            ['symbol', $body->cryptocurrency],
            ['maintenance', false]
        ])->firstOrFail();

        $uuid = Uuid::generate(4)->string;

        $payment_address = $this->getNewAddress($cryptocurrency, $uuid);

        $payment = new Payment();

        $payment->uuid = $uuid;
        $payment->payment_address = $payment_address;
        $payment->amount = $body->amount;
        $payment->cryptocurrency_id = $cryptocurrency->id;
        $payment->status = 1;
        $payment->save();

        return response()->json(Payment::find($payment->id));
    }
}
