<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HitZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hit_zones')->delete();
        $json = File::get("storage/gameData/HitZones.json");
        $zones = json_decode($json);
        $now = now();
        foreach($zones as $zone) {
            DB::insert(
                'insert into hit_zones (zone, created_at, updated_at) values (?, ?, ?)', 
                [$zone->zone, $now, $now]
            );
        }
    }
}
