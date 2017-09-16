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
        $this->dispatch(new NewAPICall($request->header('Api-Token'), $request->path(), Carbon::now()));
    }

    /**
     * @usage : /api/v1/cryptocurrency
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
