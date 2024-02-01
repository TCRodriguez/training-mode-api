<?php

namespace App\Console\Commands;

use App\Models\AttackButton;
use App\Models\Device;
use App\Models\DeviceButton;
use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AddGameDeviceMappings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:add-attack-button-mappings
                            { device : The device you wish to add attack button mappings for. }
                            { game : The game you wish to add device mappings for. (use the abbreviation like "SF6" for "Street Fighter 6") } 
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds the relationships between a device and a game\'s attack buttons.';

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
        
        try {
            //code...
            $gameArgument = strtolower($this->argument('game'));
            // dd($gameArgument);
            var_dump($gameArgument);
            $game = Game::where('abbreviation', $gameArgument)->firstOrFail();
        } catch (\Throwable $th) {
            //throw $th;
            $this->error("Game not found.");
            return Command::FAILURE;
        }
        // var_dump($game);

        $attackButtonsFile = glob("storage/gameData/*/*/{$this->argument('game')}AttackButtons.json");
        var_dump($attackButtonsFile);
        if(count($attackButtonsFile) === 0) {
            $this->error('No files found.');
            return Command::FAILURE;
        }
        // $json = File::get("storage/gameData/*/*AttackButtons.json");
        // dd($json);



        if($this->confirm("You're about to add <fg=yellow>{$this->argument('device')}</> mappings for <fg=yellow>{$game->title}</> to the database. Continue?")) {
            $json = File::get($attackButtonsFile[0]);
            $attackButtonArrayJSON = json_decode($json);
            // $attackButtonJSON = reset($attackButtonArrayJSON);
            // var_dump($attackButtonArrayJSON);
            // var_dump($attackButtonJSON);
            
            foreach($attackButtonArrayJSON as $attackButton) {
                // var_dump($attackButton);
                $attackButtonModel = AttackButton::where('name', $attackButton->name)->where('game_id', $game->id)->firstOrFail();
                
                if(isset($attackButton->device_mappings)) {
                    foreach($attackButton->device_mappings as $device_mapping) {
                        
                        // var_dump($device_mapping->device_name);
                        // var_dump($device->device_name);
                        if($device_mapping->device_name === $deviceModel->device_name) {
                            foreach($device_mapping->button_mapping as $button_mapping) {
                                $deviceButtonModel = DeviceButton::where('hardware_name', $button_mapping)->firstOrFail();
                                var_dump("Game: {$game->title}");
                                var_dump($attackButtonModel->name);
                                var_dump("Attack Button Model ID: {$attackButtonModel->id}");
                                // var_dump("Attack Button belongs to this game: {$attackButtonModel->game_id}");
                                var_dump("Device Model ID: {$deviceModel->id}");
                                var_dump("Device Button Model ID: {$deviceButtonModel->id}");
                                var_dump("Device Button Model ID: {$deviceButtonModel->face_value}");
                                var_dump("Device Button Model Name: {$deviceButtonModel->hardware_name}");

                                var_dump('===========================================================');
                                // TODO: Insert mapping into corresponding pivot table in DB
                                DB::insert(
                                    'insert into attack_button_device_button (attack_button_id, device_button_id, game_id, device_id, created_at, updated_at) values (?, ?, ?, ?, ?, ?)', 
                                    [
                                        $attackButtonModel->id, 
                                        $deviceButtonModel->id, 
                                        $attackButtonModel->game_id, 
                                        $deviceButtonModel->device_id,
                                        now(), 
                                        now()
                                    ]
                                );

                            }
                            // var_dump($device_mapping->device_name);
                            // ? Do we need to add another loop here to loop through the device buttons?
                            // ? Or should we not make `button_mapping` an array?
                            // ? We need to figure how the "any two punches" type of scenario here...
                            // $deviceButton = $device->deviceButtons()->where('hardware_name', $device_mapping->hardware_name)->firstOrFail();
                            // var_dump($deviceButton);
                            // $device->attackButtons()->attach($attackButton->id, ['device_button_id' => $deviceButton->id]);
                        }
                        // $deviceButton = $device->deviceButtons()->where('hardware_name', $device_mapping->hardware_name)->firstOrFail();
                        // var_dump($deviceButton);
                        // $device->attackButtons()->attach($attackButton->id, ['device_button_id' => $deviceButton->id]);
                    }
                }
                // TODO: Insert into corresponding pivot table in DB




                // $deviceButton = $device->deviceButtons()->where('hardware_name', $attackButton->hardware_name)->firstOrFail();
                // var_dump($deviceButton);
                // $device->attackButtons()->attach($attackButton->id, ['device_button_id' => $deviceButton->id]);
            }
        }
        return Command::SUCCESS;
    }
}
