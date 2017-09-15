<?php

namespace App\Jobs;

use App\APICall;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewAPICall implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $api_token;
    private $uri;
    private $called_at;

    /**
     * NewAPICall constructor.
     * @param APICall $apicall
     */
    public function __construct($api_token, $uri, $called_at)
    {
        $this->api_token = $api_token;
        $this->uri = $uri;
        $this->called_at = $called_at;
    }

    /**
     *
     */
    public function handle()
    {
        $apicall = new APICall();
        $apicall->user_id = User::where('api_token', $this->api_token)->first()->id;
        $apicall->uri = $this->uri;
        $apicall->called_at = $this->called_at;

        $apicall->save();
    }
}