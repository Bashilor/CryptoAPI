<?php

namespace App\Http\Controllers;

use App\Cryptocurrency;
use App\Jobs\NewAPICall;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CryptocurrencyController extends Controller
{
    /**
     * Create a new controller instance.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');

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
     *     curl -X GET -H "Api-Token: my_api_token" -i 'https://cryptoapi.com/api/v1/cryptocurrency'
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/2 200 OK
     *     [
     *          {
     *              "id": 1,
     *              "name": "Bitcoin",
     *              "symbol": "BTC",
     *              "type": "BITCOIN",
     *              "logo_url": "https://i.imgur.com/5i4e1Vi.png",
     *              "last_block_update": "2017-11-11 18:51:44",
     *              "last_btc_price": "1.00000000",
     *              "last_usd_price": "6225.42",
     *              "last_eur_price": "5287.74",
     *              "confirmations": 6,
     *              "block_explorer": "https://blockchain.info",
     *              "tx_explorer": "https://blockchain.info/en/tx/",
     *              "uri": "bitcoin",
     *              "maintenance": 1,
     *              "wallet_port": 8332,
     *              "created_at": "2017-11-11 18:51:44",
     *              "updated_at": "2017-11-11 18:53:30"
     *          },
     *          ...
     *     ]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cryptocurrencies = Cryptocurrency::all();

        return response()->json($cryptocurrencies);
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
     *     curl -X GET -H "Api-Token: my_api_token" -i 'https://cryptoapi.com/api/v1/cryptocurrency/BTC'
     *
     * @apiParam {String} symbol Symbol of the cryptocurrency.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/2 200 OK
     *     [
     *          {
     *              "id": 1,
     *              "name": "Bitcoin",
     *              "symbol": "BTC",
     *              "type": "BITCOIN",
     *              "logo_url": "https://i.imgur.com/5i4e1Vi.png",
     *              "last_block_update": "2017-11-11 18:51:44",
     *              "last_btc_price": "1.00000000",
     *              "last_usd_price": "6225.42",
     *              "last_eur_price": "5287.74",
     *              "confirmations": 6,
     *              "block_explorer": "https://blockchain.info",
     *              "tx_explorer": "https://blockchain.info/en/tx/",
     *              "uri": "bitcoin",
     *              "maintenance": 1,
     *              "wallet_port": 8332,
     *              "created_at": "2017-11-11 18:51:44",
     *              "updated_at": "2017-11-11 18:53:30"
     *          }
     *     ]
     *
     * @param $symbol
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($symbol)
    {
        $cryptocurrency = Cryptocurrency::where('symbol', $symbol)->firstOrFail();

        return response()->json($cryptocurrency);
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
     *     curl -X GET -H "Api-Token: my_api_token" -i 'https://cryptoapi.com/api/v1/cryptocurrency/list/BTC-LTC'
     *
     * @apiParam {String} symbols List of symbols of the cryptocurrencies.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/2 200 OK
     *     [
     *          {
     *              "id": 1,
     *              "name": "Bitcoin",
     *              "symbol": "BTC",
     *              "type": "BITCOIN",
     *              "logo_url": "https://i.imgur.com/5i4e1Vi.png",
     *              "last_block_update": "2017-11-11 18:51:44",
     *              "last_btc_price": "1.00000000",
     *              "last_usd_price": "6225.42",
     *              "last_eur_price": "5287.74",
     *              "confirmations": 6,
     *              "uri": "bitcoin",
     *              "maintenance": 0,
     *              "wallet_port": 8332,
     *              "created_at": "2017-11-11 18:51:44",
     *              "updated_at": "2017-11-11 18:53:30"
     *          },
     *          {
     *              "id": 2,
     *              "name": "Litecoin",
     *              "symbol": "LTC",
     *              "type": "BITCOIN",
     *              "logo_url": "https://i.imgur.com/R29q3dD.png",
     *              "last_block_update": "2017-11-11 18:51:44",
     *              "last_btc_price": "0.01006000",
     *              "last_usd_price": "62.63",
     *              "last_eur_price": "53.19",
     *              "confirmations": 6,
     *              "uri": "litecoin",
     *              "maintenance": 0,
     *              "wallet_port": 9332,
     *              "created_at": "2017-11-11 18:51:44",
     *              "updated_at": "2017-11-11 18:53:30"
     *          }
     *     ]
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
            $cryptocurrencies[] = Cryptocurrency::where('symbol', $symbol)->firstOrFail();
        }

        return response()->json($cryptocurrencies);
    }
}
