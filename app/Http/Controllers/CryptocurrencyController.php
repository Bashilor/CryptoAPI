<?php

namespace App\Http\Controllers;

use App\Cryptocurrency;
use App\Jobs\NewAPICall;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CryptocurrencyController extends Controller
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
     * @usage : /api/v1/cryptocurrency
     *
     * @apiGroup Cryptocurrency
     * @apiName GetCryptocurrencies
     * @apiVersion 1.0.0
     * @api {get}  /api/v1/cryptocurrency List all cryptocurrencies
     * @apiDescription Used to get all supported cryptocurrencies along with other meta data.
     * @apiHeader {String} Api-Token Your api-token.
     *
     * @apiExample {curl} Example usage:
     *     curl -X GET -H "Api-Token: my_api_token" -i 'https://anopay.org/api/v1/cryptocurrency'
     *
     * @apiSuccessExample Success-Response:
     * HTTP/2 200 OK
     * {
     *     "error": "",
     *     "result": {
     *         "cryptocurrencies": [
     *             {
     *                 "name": "Bitcoin",
     *                 "symbol": "BTC",
     *                 "type": "BITCOIN",
     *                 "logo_url": "https://i.imgur.com/5i4e1Vi.png",
     *                 "last_block_update": "2017-11-16 09:29:00",
     *                 "block_time": 600,
     *                 "last_usd_price": "0.00",
     *                 "last_eur_price": "0.00",
     *                 "confirmations": 6,
     *                 "block_explorer": "https://blockchain.info",
     *                 "tx_explorer": "https://blockchain.info/en/tx/",
     *                 "uri": "bitcoin",
     *                 "maintenance": "false"
     *             },
     *             ...
     *         ]
     *     }
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cryptocurrencies = Cryptocurrency::all();

        foreach ($cryptocurrencies as $cryptocurrency)
        {
            $cryptocurrency->maintenance = $cryptocurrency->maintenance == 0 ? "false" : "true";
        }

        return response()->json([
            'error' => '',
            'result' => [
                'cryptocurrencies' => $cryptocurrencies
            ]
        ]);
    }

    /**
     * @usage : /api/v1/cryptocurrency/BTC
     *
     * @apiGroup Cryptocurrency
     * @apiName GetCryptocurrency
     * @apiVersion 1.0.0
     * @api {get}  /api/v1/cryptocurrency/:symbol Get cryptocurrency
     * @apiDescription Used to get a specific supported cryptocurrency (based on his symbol) along with other meta data.
     * @apiHeader {String} Api-Token Your api-token.
     *
     * @apiExample {curl} Example usage:
     *     curl -X GET -H "Api-Token: my_api_token" -i 'https://anopay.org/api/v1/cryptocurrency/BTC'
     *
     * @apiParam {String} symbol Symbol of the cryptocurrency.
     *
     * @apiSuccessExample Success-Response:
     * HTTP/2 200 OK
     * {
     *     "error": "",
     *     "result": {
     *         "cryptocurrency": {
     *             "name": "Bitcoin",
     *             "symbol": "BTC",
     *             "type": "BITCOIN",
     *             "logo_url": "https://i.imgur.com/5i4e1Vi.png",
     *             "last_block_update": "2017-11-16 09:29:00",
     *             "block_time": 600,
     *             "last_usd_price": "0.00",
     *             "last_eur_price": "0.00",
     *             "confirmations": 6,
     *             "block_explorer": "https://blockchain.info",
     *             "tx_explorer": "https://blockchain.info/en/tx/",
     *             "uri": "bitcoin",
     *             "maintenance": "false"
     *         }
     *     }
     * }
     *
     * @param $symbol
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($symbol)
    {
        Validator::make(['symbol' => $symbol], [
            'symbol' => 'required|string|exists:mysql.cryptocurrencies,symbol'
        ])->validate();

        $cryptocurrency = Cryptocurrency::where('symbol', $symbol)->firstOrFail();

        return response()->json([
            'error' => '',
            'result' => [
                'cryptocurrency' => $cryptocurrency
            ]
        ]);
    }

    /**
     * @usage : /api/v1/cryptocurrency/list/BTC-DASH
     *
     * @apiGroup Cryptocurrency
     * @apiName GetListCryptocurrency
     * @apiVersion 1.0.0
     * @api {get}  /api/v1/cryptocurrency/list/:symbols Show list of specific cryptocurrencies
     * @apiDescription Used to get a list of specific supported cryptocurrency (based on their symbol) along with other meta data.
     * @apiHeader {String} Api-Token Your api-token.
     *
     * @apiExample {curl} Example usage:
     *     curl -X GET -H "Api-Token: my_api_token" -i 'https://anopay.org/api/v1/cryptocurrency/list/BTC-LTC'
     *
     * @apiParam {String} symbols List of symbols of the cryptocurrencies.
     *
     * @apiSuccessExample Success-Response:
     * HTTP/2 200 OK
     * {
     *     "error": "",
     *     "result": {
     *         "cryptocurrencies": [
     *             {
     *                 "name": "Bitcoin",
     *                 "symbol": "BTC",
     *                 "type": "BITCOIN",
     *                 "logo_url": "https://i.imgur.com/5i4e1Vi.png",
     *                 "last_block_update": "2017-11-16 09:29:00",
     *                 "block_time": 600,
     *                 "last_usd_price": "0.00",
     *                 "last_eur_price": "0.00",
     *                 "confirmations": 6,
     *                 "block_explorer": "https://blockchain.info",
     *                 "tx_explorer": "https://blockchain.info/en/tx/",
     *                 "uri": "bitcoin",
     *                 "maintenance": "false"
     *             },
     *             ...
     *         ]
     *     }
     * }
     *
     * @param $symbols
     * @return \Illuminate\Http\JsonResponse
     */
    public function getlist($symbols)
    {
        $symbols = explode('-', $symbols);

        $cryptocurrencies = [];
        foreach ($symbols as $symbol)
        {
            Validator::make(['symbol' => $symbol], [
                'symbol' => 'required|string|exists:mysql.cryptocurrencies,symbol'
            ])->validate();

            $cryptocurrencies[] = Cryptocurrency::where('symbol', $symbol)->firstOrFail();
        }

        return response()->json($cryptocurrencies);
    }
}
