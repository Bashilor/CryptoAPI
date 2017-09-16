<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CryptocurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('cryptocurrencies')->insert(array(
            array(
                'name'              =>  'Bitcoin',
                'symbol'            =>  'BTC',
                'logo_url'          =>  'https://i.imgur.com/5i4e1Vi.png',
                'last_block_update' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'last_usd_price'    =>  0,
                'last_btc_price'    =>  0,
                'confirmations'     =>  6,
                'block_explorer'    =>  'https://blockchain.info',
                'tx_explorer'       =>  'https://blockchain.info/en/tx/',
                'uri'               =>  'bitcoin',
                'created_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'        =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'name'              =>  'Litecoin',
                'symbol'            =>  'LTC',
                'logo_url'          =>  'https://i.imgur.com/R29q3dD.png',
                'last_block_update' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'last_usd_price'    =>  0,
                'last_btc_price'    =>  0,
                'confirmations'     =>  6,
                'block_explorer'    =>  'https://live.blockcypher.com/ltc',
                'tx_explorer'       =>  'https://live.blockcypher.com/ltc/tx/',
                'uri'               =>  'litecoin',
                'created_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'        =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'name'              =>  'Dash',
                'symbol'            =>  'DASH',
                'logo_url'          =>  'https://i.imgur.com/qiIMHZr.png',
                'last_block_update' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'last_usd_price'    =>  0,
                'last_btc_price'    =>  0,
                'confirmations'     =>  6,
                'block_explorer'    =>  'https://explorer.dash.org',
                'tx_explorer'       =>  'https://explorer.dash.org/tx/',
                'uri'               =>  'dash',
                'created_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'        =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'name'              =>  'PIVX',
                'symbol'            =>  'PIVX',
                'logo_url'          =>  'https://i.imgur.com/c689IAo.png',
                'last_block_update' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'last_usd_price'    =>  0,
                'last_btc_price'    =>  0,
                'confirmations'     =>  6,
                'block_explorer'    =>  'http://pivxscan.io',
                'tx_explorer'       =>  'http://pivxscan.io/tx/',
                'uri'               =>  'pivx',
                'created_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'        =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'name'              =>  'Nexus',
                'symbol'            =>  'NXS',
                'logo_url'          =>  'https://i.imgur.com/Sf3N92M.png',
                'last_block_update' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'last_usd_price'    =>  0,
                'last_btc_price'    =>  0,
                'confirmations'     =>  6,
                'block_explorer'    =>  'http://nxs.efficienthash.com',
                'tx_explorer'       =>  'http://nxs.efficienthash.com/txes/',
                'uri'               =>  'nexus',
                'created_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'        =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'name'              =>  'Dogecoin',
                'symbol'            =>  'DOGE',
                'logo_url'          =>  'https://i.imgur.com/e1RS4Hn.png',
                'last_block_update' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'last_usd_price'    =>  0,
                'last_btc_price'    =>  0,
                'confirmations'     =>  3,
                'block_explorer'    =>  'https://dogechain.info',
                'tx_explorer'       =>  'https://dogechain.info/tx/',
                'uri'               =>  'dogecoin',
                'created_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'        =>  Carbon::now()->format('Y-m-d H:i:s')
            )
        ));
    }
}