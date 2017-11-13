<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class APICall extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'apicalls';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'uri', 'called_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * Get the user related to the api call.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
