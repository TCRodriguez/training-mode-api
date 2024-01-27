<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\GameDeviceAttackButtonMapping;
use App\Models\GameDeviceDirectionalInputMapping;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::all();

        return $devices;
    }

    public function show(Request $request, $deviceId)
    {
        // $games = Game::with(['notes.tags' => function ($query) use ($request) {
        //     $query->where('user_id', $request->user()->id);
        // }])->get();
        $device = Device::where('id', $deviceId)->with('deviceButtons')->firstOrFail();


        return $device;
    }

    public function showWithAttackButtonMappings(Request $request, $deviceId, $gameId)
    {
        // $games = Game::with(['notes.tags' => function ($query) use ($request) {
        //     $query->where('user_id', $request->user()->id);
        // }])->get();
        $device = Device::where("id", $deviceId)
        ->with([
            "buttons" => function ($query) use ($gameId) {
            $query->with([
                "gameDeviceAttackButtonMappings" => function ($query) use ($gameId) {
                $query->where("game_id", $gameId)->with("attackButton"); // Assuming this relationship exists in your pivot model
                }
            ]);
            }
        ])
        ->first();

        return $device;
    }

    public function getGameDeviceInputMappings(Request $request, $gameId, $deviceId)
    {
        $gameDeviceAttackButtonMappings = GameDeviceAttackButtonMapping::where('device_id', $deviceId)->where('game_id', $gameId)->get();
        $gameDeviceDirectionalInputMappings = GameDeviceDirectionalInputMapping::where('device_id', $deviceId)->get();

        // $gameDeviceInputMappings = $gameDeviceAttackButtonMappings->merge($gameDeviceDirectionalInputMappings);
        $gameDeviceInputMappings = array_merge($gameDeviceAttackButtonMappings->toArray(), $gameDeviceDirectionalInputMappings->toArray());
        // $mappings = $device->mappings;

        // return $gameDeviceAttackButtonMappings;
        // return $gameDeviceDirectionalInputMappings;

        return $gameDeviceInputMappings;
    }
}
