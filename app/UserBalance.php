<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_balances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'cryptocurrency_id', 'balance'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'user_id'
    ];

    /**
     * Get the user related to the balance.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the cryptocurrency related to the balance.
     */
    public function cryptocurrency()
    {
        return $this->belongsTo('App\Cryptocurrency');
    }
}
