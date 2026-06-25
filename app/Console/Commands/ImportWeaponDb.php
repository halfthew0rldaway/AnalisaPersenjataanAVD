<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('app:import-weapon-db')]
#[Description('Import the weapon DB CSV')]
class ImportWeaponDb extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = base_path('weapondb.csv');
        if (!file_exists($file)) {
            $this->error('weapondb.csv not found!');
            return;
        }

        $this->info('Truncating table...');
        DB::table('global_weapons_systems')->truncate();

        $this->info('Reading CSV...');
        $handle = fopen($file, 'r');
        $headers = fgetcsv($handle); 

        $batch = [];
        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = [];
            foreach ($headers as $index => $header) {
                if ($header == 'ID') continue;
                $data[$header] = $row[$index] === '' ? null : $row[$index];
            }
            
            $batch[] = $data;

            if (count($batch) >= 500) {
                DB::table('global_weapons_systems')->insert($batch);
                $count += count($batch);
                $this->info("Imported $count records...");
                $batch = [];
            }
        }

        if (count($batch) > 0) {
            DB::table('global_weapons_systems')->insert($batch);
            $count += count($batch);
        }

        fclose($handle);
        $this->info("Successfully imported $count records!");
    }
}
