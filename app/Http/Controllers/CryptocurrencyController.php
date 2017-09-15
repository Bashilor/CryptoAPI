<?php

namespace App\Http\Controllers;

use App\Cryptocurrency;

class CryptocurrencyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cryptocurrencies = Cryptocurrency::all();

        return response()->json($cryptocurrencies);
    }

    /**
     * @param $symbol
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($symbol)
    {
        $cryptocurrency = Cryptocurrency::where('symbol', $symbol)->first();

        return response()->json($cryptocurrency);
    }
}
