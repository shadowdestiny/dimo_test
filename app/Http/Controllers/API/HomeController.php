<?php

namespace App\Http\Controllers\API;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Resources\HomeResource;
use App\Jobs\CheckDue;
use App\Jobs\VerifyLevel;

class HomeController extends Controller
{
    /**
     * Show home info.
     *
     * @param Client $client
     */
    public function index(Client $client)
    {
        return new HomeResource($client);
    }

    public function send()
    {
        VerifyLevel::dispatch();
        CheckDue::dispatch();
    }
}
