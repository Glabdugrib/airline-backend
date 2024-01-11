<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get and decode airports JSON data
        $jsonFilePath = storage_path('app/airports.json');
        $jsonFile = File::get($jsonFilePath);
        $data = json_decode($jsonFile, true);

        // Add timestamps to each record
        $now = now();
        $data = collect($data)->map(function ($item) use ($now) {
            return array_merge($item, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        })->toArray();

        // Insert data into the database
        DB::table('airports')->insert($data);
    }
}
