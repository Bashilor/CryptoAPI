<?php

namespace App;

use App\Events\PaymentSuccessful;
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
     * The events associated with the model.
     *
     * @var array
     */
    protected $events =[
        'updated' => PaymentSuccessful::class
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'payment_address', 'amount', 'cryptocurrency_id', 'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];
}
