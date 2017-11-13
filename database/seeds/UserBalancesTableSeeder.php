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
                'user_id'            =>  '1',
                'cryptocurrency_id'  =>  '1',
                'balance'            =>  0.00000000,
                'created_at'         =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'user_id'            =>  '1',
                'cryptocurrency_id'  =>  '2',
                'balance'            =>  0.00000000,
                'created_at'         =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'user_id'            =>  '1',
                'cryptocurrency_id'  =>  '3',
                'balance'            =>  0.00000000,
                'created_at'         =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'user_id'            =>  '1',
                'cryptocurrency_id'  =>  '4',
                'balance'            =>  0.00000000,
                'created_at'         =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'user_id'            =>  '1',
                'cryptocurrency_id'  =>  '5',
                'balance'            =>  0.00000000,
                'created_at'         =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'user_id'            =>  '1',
                'cryptocurrency_id'  =>  '6',
                'balance'            =>  0.00000000,
                'created_at'         =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         =>  Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'user_id'            =>  '1',
                'cryptocurrency_id'  =>  '7',
                'balance'            =>  0.00000000,
                'created_at'         =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'         =>  Carbon::now()->format('Y-m-d H:i:s')
            )
        ));
    }
}
