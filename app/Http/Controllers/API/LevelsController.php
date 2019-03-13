<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LevelResource;
use App\Level;
use App\Setting;
use Illuminate\Http\Request;

class LevelsController extends Controller
{
    /**
     * Level Instance.
     *
     * @var \App\Level
     */
    protected $levels;

    /**
     * Create controller instance.
     *
     * @param \App\Level levels
     */
    public function __construct(Level $levels)
    {
        $this->levels = $levels;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $levels = $this->levels->orderBy("order","asc")->get();

        return LevelResource::collection($levels);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Level $level
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Level $level)
    {
        return new LevelResource($level);
    }

    public function detail(Request $request)
    {
        $level = Level::whereUuid($request->get('level_uuid'))->first();

        return new LevelResource($level);
    }

    public function update(Request $request)
    {
        $setting = Setting::where("key","=",Setting::ANNUAL_RATE)->first();
        $setting->value = $request["annual_interest_rate"];
        $setting->save();

        $level = Level::find($request->uuid);
        $level->fill($request->all());
        $level->save();

        return response()->json(['successful' => 'Ok'], 200);
    }
}
