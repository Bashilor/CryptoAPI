<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cryptocurrency extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cryptocurrencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'symbol', 'type', 'logo_url', 'last_block_update', 'last_btc_price', 'last_usd_price', 'last_eur_price', 'confirmations', 'block_explorer', 'tx_explorer', 'uri', 'maintenance', 'wallet_port'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];
}
