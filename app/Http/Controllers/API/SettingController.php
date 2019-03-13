<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    //TODO: Improvement action
    public function setting()
    {
        $setting = [
            'minimun_to_apply'   => Setting::where('key', Setting::MINIMUN_TO_APPLY)->first()->value,
            'maximum_to_apply'   => Setting::where('key', Setting::MAXIMUM_TO_APPLY)->first()->value,
            'maximum_next_level' => Setting::where('key', Setting::MAXIMUM_NEXT_LEVEL)->first()->value,
            'pay_next'           => Setting::where('key', Setting::PAY_NEXT)->first()->value,
        ];

        return response()->json($setting, 200);
    }

    //TODO: Improvement action
    public function update(Request $request)
    {
        Setting::MAXIMUM_TO_APPLY;
        foreach ($request->all() as $key => $value) {
            $key_db         = Setting::SETTING_KEYS[strtoupper($key)];
            $setting        = Setting::where('settings.key', '=', $key_db)->first();
            $setting->value = $value;
            $setting->save();
        }

        return response()->json(['successful' => 'Ok'], 200);
    }
}
