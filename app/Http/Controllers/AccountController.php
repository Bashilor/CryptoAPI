<?php

namespace App\Http\Controllers;

use App\Cryptocurrency;
use App\User;
use App\Jobs\NewAPICall;
use App\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JsonRpc\Client;
use Webpatser\Uuid\Uuid;

class AccountController extends Controller
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
     * @usage : /api/v1/account/balance
     *
     * @apiGroup Account
     * @apiName GetAccountBalance
     * @apiVersion 1.0.0
     * @api {get}  /api/v1/account/balance Get balance
     * @apiDescription Used to get all of the user's balance.
     * @apiHeader {String} Api-Token Your api-token.
     *
     * @apiExample {curl} Example usage:
     *     curl -X GET -H "Api-Token: my_api_token" -i 'https://anopay.org/api/v1/account/balance'
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/2 200 OK
     *     [
     *          {
     *              "cryptocurrency": "BTC",
     *              "balance": "0.12345678",
     *              "updated_at": "2017-11-15 12:18:08"
     *          },
     *          ...
     *     ]
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance(Request $request)
    {
        $user = User::where('api_token', $request->header('Api-Token'))->first();

        $balances = User::find($user->id)->balance;

        return response()->json([
            'error' => '',
            'result' => [
                'balances' => $balances
            ]
        ]);
    }

    /**
     * @param $cryptocurrency
     * @param $payout_address
     * @param $amount
     * @param $uuid
     * @return mixed
     */
    public function getNewTransaction($cryptocurrency, $payout_address, $amount, $uuid)
    {
        $wallet_uri = 'http://'. $this->wallet_username .':'. $this->wallet_password .'@'. $this->wallet_ip_address .':'. $cryptocurrency->wallet_port .'/';
        $client = new Client($wallet_uri);

        if($cryptocurrency->type == 'BITCOIN' || $cryptocurrency->type == 'BITCOINEX')
        {
            $client->call('sendtoaddress', [$payout_address, $amount * 1e8, $uuid]);
            $txID = json_decode($client->output)->result;
        }

        return $txID;
    }

    /**
     * @usage : /api/v1/account/withdraw
     *
     * @apiGroup Account
     * @apiName CreateWithdraw
     * @apiVersion 1.0.0
     * @api {post}  /api/v1/account/withdraw Create withdraw
     * @apiDescription Used to create a withdraw identified by a unique ID (UUID v4).
     * @apiHeader {String} Api-Token Your api-token.
     * @apiHeader {String="application/json"} Content-Type Type of the content sent.
     *
     * @apiExample {curl} Example usage:
     *     curl -X POST -H "Api-Token: my_api_token" -H "Content-Type: application/json" -i 'https://anopay.org/api/v1/account/withdraw' -d '{"cryptocurrency": "BTC", "amount": 123.456, "payout_address": "1PS3iuSLsJBiPZfTra9pBc7g8zr4myL1UV"}'
     *
     * @apiParam {String="BTC","LTC","DASH","PIVX","NXS","DOGE"} cryptocurrency Symbol of the cryptocurrency.
     * @apiParam {Decimal{8-16}} amount A positive Decimal (up to the smallest cryptocurrency unit a.k.a satoshi; e.g. 0.00000001).
     * @apiParam {String} payout_address Wallet / exchange address to receive coins.
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/2 200 OK
     *  {
     *      "error": "",
     *      "result": {
     *          "withdraw": {
     *              "uuid": "e63d9240-f58d-4793-83ba-03ccc654fb34",
     *              "payout_address": "1PS3iuSLsJBiPZfTra9pBc7g8zr4myL1UV",
     *              "amount": 123.456,
     *              "cryptocurrency": "BTC",
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
    public function withdraw(Request $request)
    {
        $body = json_decode($request->getContent());

        Validator::make($request->json()->all(), [
            'cryptocurrency' => 'required|string|exists:mysql.cryptocurrencies,symbol',
            'amount' => 'required|regex:/^([\d]{1,8}([.][\d]{0,8})?)$/',
            'payout_address' => 'required|string'
        ])->validate();

        $cryptocurrency = Cryptocurrency::where([
            ['symbol', $body->cryptocurrency],
            ['maintenance', false]
        ])->firstOrFail();

        $uuid = Uuid::generate(4)->string;

        $txID = $this->getNewTransaction($cryptocurrency, $body->payout_address, $body->amount, $uuid);

        $withdraw = new Withdraw();

        $withdraw->user_id = User::where('api_token', $request->header('Api-Token'))->first()->id;
        $withdraw->uuid = $uuid;
        $withdraw->payout_address = $body->payout_address;
        $withdraw->amount = $body->amount * 1e8;
        $withdraw->cryptocurrency_id = $cryptocurrency->id;
        $withdraw->cryptocurrency = $cryptocurrency->symbol;
        $withdraw->status = 2;
        $withdraw->transaction_id = $txID;
        $withdraw->save();

        $withdraw = Withdraw::find($withdraw->id);
        $withdraw->status = $withdraw->status == 1 ? "pending" : ($withdraw->status == 2 ? "confirmed" : "cancelled");
        $withdraw->amount = $withdraw->amount / 1e8;

        return response()->json([
            'error' => '',
            'result' => [
                'withdraw' => $withdraw
            ]
        ]);
    }
}
