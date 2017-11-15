<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserBalancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('user_balances')->insert(array(
            array(
                'user_id'               =>  '1',
                'cryptocurrency_id'     =>  '1',
                'cryptocurrency'   =>  'BTC',
                'balance'               =>  0,
                'created_at'            =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'            =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'user_id'               =>  '1',
                'cryptocurrency_id'     =>  '2',
                'cryptocurrency'   =>  'LTC',
                'balance'               =>  0,
                'created_at'            =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'            =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'user_id'               =>  '1',
                'cryptocurrency_id'     =>  '3',
                'cryptocurrency'   =>  'DASH',
                'balance'               =>  0,
                'created_at'            =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'            =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'user_id'               =>  '1',
                'cryptocurrency_id'     =>  '4',
                'cryptocurrency'   =>  'PIVX',
                'balance'               =>  0,
                'created_at'            =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'            =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'user_id'               =>  '1',
                'cryptocurrency_id'     =>  '5',
                'cryptocurrency'   =>  'NXS',
                'balance'               =>  0,
                'created_at'            =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'            =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'user_id'               =>  '1',
                'cryptocurrency_id'     =>  '1',
                'cryptocurrency'   =>  'DOGE',
                'balance'               =>  0,
                'created_at'            =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'            =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'user_id'               =>  '1',
                'cryptocurrency_id'     =>  '6',
                'cryptocurrency'   =>  'ZEC',
                'balance'               =>  0,
                'created_at'            =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'            =>  Carbon::now()->format('Y-m-d H:i:s')
            )
        ));
    }
}
