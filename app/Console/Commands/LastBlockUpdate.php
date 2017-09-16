<?php

namespace App\Console\Commands;

use App\Cryptocurrency;
use Carbon\Carbon;
use Illuminate\Console\Command;
use JsonRpc\Client;

class LastBlockUpdate extends Command
{
    private $wallet_username;
    private $wallet_password;
    private $wallet_ip_address;

    private $btc_wallet_port;
    private $ltc_wallet_port;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cryptos:lastblockupdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the last block update of each cryptocurrencies.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->wallet_username       = env('WALLET_USERNAME');
        $this->wallet_password       = env('WALLET_PASSWORD');
        $this->wallet_ip_address     = env('WALLET_IP_ADDRESS');

        $this->btc_wallet_port       = env('BTC_WALLET_PORT');
        $this->ltc_wallet_port       = env('LTC_WALLET_PORT');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cryptocurrencies = Cryptocurrency::where('maintenance', false)->get();
        foreach ($cryptocurrencies as $cryptocurrency)
        {
            $wallet_uri = 'http://'. $this->wallet_username .':'. $this->wallet_password .'@'. $this->wallet_ipaddress .':'. $this->{strtolower($cryptocurrency->symbol).'_wallet_port'} .'/';

            $client = new Client($wallet_uri);

            $client->call('getblockcount', []);
            $getBlockCount = json_decode($client->output)->result;

            $client->call('getblockhash', [$getBlockCount]);
            $blockHash = json_decode($client->output)->result;

            $client->call('getblock', [$blockHash]);
            $lastBlock = json_decode($client->output)->result->time;

            $lastBlockUpdate = Carbon::createFromTimestamp($lastBlock)->toDateTimeString();

            $cryptocurrency->last_block_update = $lastBlockUpdate;
            $cryptocurrency->save();
        }
    }
}
