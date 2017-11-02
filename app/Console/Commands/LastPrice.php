<?php

namespace App\Console\Commands;

use App\Cryptocurrency;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class LastPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cryptos:lastprice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the last price (BTC / USD) of each cryptocurrencies.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $response = $client->get('https://blockchain.info/fr/ticker');

        $response = json_decode($response->getBody()->getContents(), true);

        $btcPrice_usd = $response["USD"]["last"];
        $btcPrice_eur = $response["EUR"]["last"];

        $cryptocurrencies = Cryptocurrency::where('maintenance', false)->get();
        foreach ($cryptocurrencies as $cryptocurrency)
        {
            if($cryptocurrency->symbol != "BTC")
            {
                $bittrex_market = 'https://bittrex.com/api/v1.1/public/getmarketsummary?market=btc-'.$cryptocurrency->symbol;

                $client = new Client();
                $response = $client->get($bittrex_market);

                $response = json_decode($response->getBody()->getContents(), true);

                $lastPrice = number_format($response["result"][0]["Last"], 8);

                $cryptocurrency->last_usd_price = number_format($lastPrice * $btcPrice_usd, 2);
                $cryptocurrency->last_eur_price = number_format($lastPrice * $btcPrice_eur, 2);
                $cryptocurrency->last_btc_price = $lastPrice;
                $cryptocurrency->save();
            }
            else
            {
                $cryptocurrency->last_usd_price = $btcPrice_usd;
                $cryptocurrency->last_eur_price = $btcPrice_eur;
                $cryptocurrency->last_btc_price = 1;
                $cryptocurrency->save();
            }
        }
    }
}
