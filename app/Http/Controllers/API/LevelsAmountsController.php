<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LevelAmountResource;
use App\LevelAmount;
use Illuminate\Http\Request;

class LevelsAmountsController extends Controller
{
    //TODO: Improvement action
    public function listAmount($level_uuid)
    {
        $levelAmount = LevelAmount::where('level_amounts.level_uuid', '=', $level_uuid)
            ->where("available","=",true)
            ->orderBy('amount','asc')
            ->get();

        return LevelAmountResource::collection($levelAmount);
    }

    public function update(Request $request)
    {
        $level = LevelAmount::find($request->uuid);
        $level->fill($request->all());
        $level->save();

        return response()->json(['successful' => 'Ok'], 200);
    }
}
