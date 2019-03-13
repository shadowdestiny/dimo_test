<?php

namespace App\Http\Controllers\API;

use App\ClientWallet;
use App\Http\Controllers\Controller;

class ClientWalletsController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ClientWallet $wallet
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ClientWallet $wallet)
    {
        $wallet->delete();

        return response()->json([], 204);
    }
}
