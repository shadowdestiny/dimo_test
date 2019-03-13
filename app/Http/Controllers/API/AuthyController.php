<?php

namespace App\Http\Controllers\API;

use App\Client;
use App\Contracts\Authy;
use App\Http\Controllers\Controller;
use App\Level;
use Illuminate\Http\Request;

class AuthyController extends Controller
{
    /**
     * Authy instance.
     *
     * @var \App\Contracts\Authy
     */
    protected $authy;

    /**
     * Client instance.
     *
     * @var \App\Client
     */
    protected $client;

    /**
     * Level Instance.
     *
     * @var \App\Level
     */
    protected $levels;

    /**
     * Create instance controller.
     *
     * @param \App\Contracts\Authy $authy
     * @param \App\Client          $client
     * @param \App\Level           $level
     */
    public function __construct(Authy $authy, Client $client, Level $levels)
    {
        $this->authy  = $authy;
        $this->client = $client;
        $this->levels = $levels;
    }

    /**
     * Send code confirmation via sms or call.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function send(Request $request)
    {
        $user = $this->authy->send($request->number_phone, $request->type);
        if ($user->ok()) {
            return response()->json([
                        'message' => $user->message(),
                        'status'  => $user->ok(),
                   ]);
        }

        return response()->json($user->errors());
    }

    /**
     * Verify code confirmation send to phone.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function verify(Request $request)
    {
        $verify   = $this->authy->verify($request->number_phone, $request->code);
        $is_new   = false;
        if ($verify->ok()) {
            if ($this->client->isNewClient($request->number_phone)) {
                $client = $this->client->create([
                    'number_phone' => $request->number_phone,
                    'level_uuid'   => $this->levels->where('key', 'silver')->first()->uuid,
                    'verified'     => true,
                ]);
                $is_new = true;
            } else {
                $client = $this->client->whereNumberPhone($request->number_phone)->first();
            }

            return response()->json([
                        'message'     => $verify->message(),
                        'verified'    => $verify->ok(),
                        'is_new'      => $is_new,
                        'client_uuid' => $client->uuid,
                   ]);
        }

        return response()->json([
                    'message'  => $verify->message(),
                    'verified' => false,
                ]);
    }
}
