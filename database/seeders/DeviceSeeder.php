<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DeviceButton;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("storage/gameData/Devices.json");
        $devices = json_decode($json);

        // dd($devices);

        foreach($devices as $device) {
            $newDevice = Device::create([
                "device_name" => $device->device_name,
                "device_hardware_id" => $device->device_hardware_id,
                "device_type" => $device->device_type,
            ]);

            // dd($newDevice);

            foreach($device->device_buttons as $button) {
                $deviceModel = Device::where('device_hardware_id', $device->device_hardware_id)->firstOrFail();
                $deviceId = $deviceModel->id;

                $deviceButton = DeviceButton::create([
                    "hardware_name" => $button->hardware_name,
                    "face_value" => $button->face_value,
                    "category" => $button->category,
                    "device_id" => '1',
                ]);

                // var_dump($deviceButton);
            }
        }
        

    }
}
