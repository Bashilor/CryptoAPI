<?php

namespace App\Http\Controllers;

use App\User;
use App\Jobs\NewAPICall;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $apiCall = (new NewAPICall($request->header('Api-Token'), $request->path(), Carbon::now()))->onQueue('api');
        $this->dispatch($apiCall);
    }

    /**
     * @usage : /api/v1/account/balance
     *
     * @apiGroup Account
     * @apiName GetAccountBalance
     * @apiVersion 1.0.0
     * @api {get}  /api/v1/account/balance Get account balance
     * @apiDescription Used to get all of the user's balance.
     * @apiHeader {String} Api-Token Your api-token.
     *
     * @apiExample {curl} Example usage:
     *     curl -X GET -H "Api-Token: my_api_token" -i 'https://cryptoapi.com/api/v1/account/balance'
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/2 200 OK
     *     [
     *          {
     *              "cryptocurrency_id": 1,
     *              "balance": "0.12345678"
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

        return response()->json($balances);
    }
}
