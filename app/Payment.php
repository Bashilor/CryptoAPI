<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'uuid', 'payment_address', 'amount', 'cryptocurrency_id', 'cryptocurrency', 'status'
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
