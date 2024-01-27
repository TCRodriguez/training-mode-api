<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Models\DeviceButton;
use App\Models\DirectionalInput;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AddDeviceDirectionalInputMappings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:add-directional-input-mappings
                            { device : The device you wish to add directional input mappings for. }
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds the mappings for all directional inputs for a device.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        try {
            //code...
            var_dump($this->argument('device'));
            $deviceModel = Device::where('device_name', $this->argument('device'))->firstOrFail();
        } catch (\Throwable $th) {
            //throw $th;
            $this->error("Device not found.");
            return Command::FAILURE;
        }
        if($this->confirm("You're about to add directional input mappings for <fg=yellow>{$deviceModel->device_name}</>. Continue?")) {
            $this->info("Adding directional input mappings for {$deviceModel->device_name}.");
        } else {
            $this->error("Directional input mappings for {$deviceModel->device_name} not added.");
            return Command::FAILURE;
        }
        $devicesJson = File::get("storage/gameData/Devices.json");
        $deviceArray = json_decode($devicesJson);

        $directionalInputsJson = File::get('storage/gameData/DirectionalInputs.json');
        $directionalInputsArray = json_decode($directionalInputsJson);

        $diagonalDirections = ['1', '3', '7', '9'];
        
        foreach($directionalInputsArray as $directionalInput) {
            $directionalInputModel = DirectionalInput::where('direction', $directionalInput->direction)->firstOrFail();
            var_dump("Directional Input: $directionalInputModel->direction");
            var_dump("Directional Input ID: $directionalInputModel->id");
            // var_dump("{$directionalInput->direction}");

            if(isset($directionalInput->device_mappings)) {
                foreach($directionalInput->device_mappings as $deviceMapping) {
                    if($deviceMapping->device_name === $this->argument('device')) {
                        foreach($deviceMapping->button_mapping as $buttonMapping) {
                            $deviceButtonModel = DeviceButton::where('hardware_name', $buttonMapping)->firstOrFail();
                            var_dump("Device Button: $deviceButtonModel->hardware_name");
                            var_dump("Device Button ID: $deviceButtonModel->id");
                            var_dump("Device Button Face value: $deviceButtonModel->face_value");

                            // $attackButton = DeviceButton::where('hardware_name', $buttonMapping->hardware_name)->firstOrFail();
                            $diagonalDirectionBool = in_array($directionalInputModel->numpad_notation, $diagonalDirections);
                            DB::insert('insert into device_button_directional_input (device_button_id, directional_input_id, game_shorthand, diagonal_direction, device_id, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [$deviceButtonModel->id, $directionalInputModel->id, $directionalInputModel->game_shorthand, $diagonalDirectionBool, $deviceButtonModel->device_id, now(), now()]);
                        };
                        // DB::insert('insert into device_button_directional_input (attack_button_id, device_button_id) values (?, ?)', [$attackButton->id, $deviceButtonModel->id]);
                    }
                    // if($device->device_name === $this->argument('device')) {
                        // $deviceModel->directionalInputs()->create([
                        //     "hardware_name" => $directionalInput->hardware_name,
                        //     "face_value" => $directionalInput->face_value,
                        //     "category" => $directionalInput->category,
                        // ]);
                    // }
                    // var_dump($deviceMapping->device_name);
                }
                var_dump('===================================================================================');
            }
            // var_dump($directionalInput->direction);
            // $deviceModel->directionalInputs()->create([
            //     "hardware_name" => $directionalInput->hardware_name,
            //     "face_value" => $directionalInput->face_value,
            //     "category" => $directionalInput->category,
            // ]);
        }





        return Command::SUCCESS;
    }
}
