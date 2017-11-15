<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'withdraws';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'uuid', 'payout_address', 'amount', 'cryptocurrency_id', 'cryptocurrency', 'status', 'transaction_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'user_id', 'cryptocurrency_id'
    ];

    /**
     * Get the user related to the payment.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the cryptocurrency related to the payment.
     */
    public function cryptocurrency()
    {
        return $this->belongsTo('App\Cryptocurrency');
    }
}
