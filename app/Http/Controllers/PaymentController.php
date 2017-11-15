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
     * @usage : /payments/payment
     *
     * @apiGroup Payment
     * @apiName 1_CreatePayment
     * @apiVersion 1.0.0
     * @api {post}  /api/v1/payments/payment Create payment
     * @apiDescription Used to create a payment form identified by a unique ID (UUID v4).
     * @apiHeader {String} Api-Token Your api-token.
     * @apiHeader {String="application/json"} Content-Type Type of the content sent.
     *
     * @apiExample {curl} Example usage:
     *     curl -X POST -H "Api-Token: my_api_token" -H "Content-Type: application/json" -i 'https://anopay.org/api/v1/payments/payment' -d '{"cryptocurrency": "BTC", "amount": 123456}'
     *
     * @apiParam {String="BTC","LTC","DASH","PIVX","NXS","DOGE"} cryptocurrency Symbol of the cryptocurrency.
     * @apiParam {BigInteger{1-16}} amount A positive BigInteger in the smallest cryptocurrency unit (satoshi, e.g. 100000000 to charge 1 coin). <br/> Minimal amount is a satoshi (100000000 / 1e8).
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/2 200 OK
     *  {
     *      "error": "",
     *      "result": {
     *          "payment": {
     *              "uuid": "e63d9240-f58d-4793-83ba-03ccc654fb34",
     *              "payment_address": "122",
     *              "amount": 123,
     *              "cryptocurrency": "NXS",
     *              "status": "pending",
     *              "created_at": "2017-11-15 12:18:08",
     *              "updated_at": "2017-11-15 12:18:08"
     *          }
     *      }
     *  }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $body = json_decode($request->getContent());

        Validator::make($request->json()->all(), [
            'cryptocurrency' => 'required|string|exists:mysql.cryptocurrencies,symbol',
            'amount' => 'required|digits_between:1,16'
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
        $payment->cryptocurrency = $cryptocurrency->symbol;
        $payment->status = 1;
        $payment->save();

        $payment = Payment::find($payment->id);
        $payment->status = $payment->status == 1 ? "pending" : ($payment->status == 2 ? "confirmed" : "cancelled");

        return response()->json([
            'error' => '',
            'result' => [
                'payment' => $payment
            ]
        ]);
    }

    /**
     * @usage : /payments/payment
     *
     * @apiGroup Payment
     * @apiName 2_ListPayment
     * @apiVersion 1.0.0
     * @api {get}  /api/v1/payments/payment List payments
     * @apiDescription Used to list payments create by the create payment call and that are in any state.
     * @apiHeader {String} Api-Token Your api-token.
     *
     * @apiExample {curl} Example usage:
     *     curl -X POST -H "Api-Token: my_api_token" -i 'https://anopay.org/api/v1/payments/payment'
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/2 200 OK
     *  {
     *      "error": "",
     *      "result": {
     *          "payments": [
     *              {
     *                  "uuid": "e63d9240-f58d-4793-83ba-03ccc654fb34",
     *                  "payment_address": "122",
     *                  "amount": 123,
     *                  "cryptocurrency": "NXS",
     *                  "status": "confirmed",
     *                  "created_at": "2017-11-15 12:18:08",
     *                  "updated_at": "2017-11-15 12:18:08"
     *              },
     *              ...
     *          ]
     *      }
     *  }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getlist(Request $request)
    {
        $user = User::where('api_token', $request->header('Api-Token'))->first();

        $payments = User::find($user->id)->payment;

        foreach ($payments as $payment)
        {
            $payment->status = $payment->status == 1 ? "pending" : ($payment->status == 2 ? "confirmed" : "cancelled");
        }

        return response()->json([
            'error' => '',
            'result' => [
                'payments' => $payments
            ]
        ]);
    }
}
