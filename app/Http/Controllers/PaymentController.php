<?php

namespace App\Http\Controllers;

use App\Cryptocurrency;
use App\Payment;
use App\Jobs\NewAPICall;
use App\User;
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
        $apiCall = (new NewAPICall($request->header('Api-Token'), $request->path(), Carbon::now()))->onQueue('api');
        $this->dispatch($apiCall);

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

        if($cryptocurrency->type == 'BITCOIN')
        {
            $client->call('getnewaddress', [$uuid]);
            $newAddress = json_decode($client->output)->result;
        }
        elseif ($cryptocurrency->type == 'BITCOINEX')
        {
            $client->call('getnewaddress', []);
            $newAddress = json_decode($client->output)->result;
        }

        return $newAddress;
    }

    /**
     * @usage : /payment/new
     *
     * @apiGroup Payment
     * @apiName CreatePayment
     * @apiVersion 1.0.0
     * @api {post}  /api/v1/payment/new Create a new payment
     * @apiDescription Used to create a new payment form identified by a unique ID (UUID v4).
     * @apiHeader {String} Api-Token Your api-token.
     * @apiHeader {String} Content-Type Type of the content sent. <br/> Allowed value : <code>application/json</code>
     *
     * @apiExample {curl} Example usage:
     *     curl -X POST -H "Api-Token: my_api_token" -H "Content-Type: application/json" -i 'http://localhost:8000/api/v1/payment/new' -d '{"cryptocurrency": "BTC", "amount": "123.456"}'
     *
     * @apiParam {String} cryptocurrency Symbol of the cryptocurrency.
     * @apiParam {Decimal} amount Amount is a decimal (8, 16).
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/2 200 OK
     *     {
     *          "uuid": "6778571c-f564-4f85-bdf1-8a6ca24cdce6",
     *          "payment_address": "12oPLQVkPHkeAzrM1hKPh6K6krzrJtdp1p",
     *          "amount": "1.00000000",
     *          "cryptocurrency_id": 1,
     *          "status": 1,
     *          "created_at": "2017-11-12 16:57:12",
     *          "updated_at": "2017-11-12 16:57:12"
     *     }
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

        $payment->user_id = User::where('api_token', $request->header('Api-Token'))->first()->id;
        $payment->uuid = $uuid;
        $payment->payment_address = $payment_address;
        $payment->amount = $body->amount;
        $payment->cryptocurrency_id = $cryptocurrency->id;
        $payment->status = 1;
        $payment->save();

        return response()->json(Payment::find($payment->id));
    }
}
