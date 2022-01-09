<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $csvFile = fopen(base_path("database/data/test.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                User::create([
                    "name" => $data['0'],
                    'username' => Str::slug($data['0'], '.'),
                    "seat_count" => $data['1'],
                    "password" => Hash::make($data['2'])
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}