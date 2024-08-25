<?php

namespace App\Console\Commands;

use App\Models\ItemsList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddItemsInDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-items-in-database-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $filePath = storage_path('app/Groceries_dataset_translated.csv'); // sau 'public/Groceries_dataset_translated.csv'

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {

                $insert = [];

                if ($data[0] === 'Member_number')
                    continue;

                if (!ItemsList::where('name', $data[2])->first()) {
                    $insert[] = [
                        'name' => $data[2],
                        'used' => 0,
                        'language' => 'en'
                    ];
                }

                if (!empty($data[3]) && !ItemsList::where('name', $data[3])->first()) {
                    $insert[] = [
                        'name' => $data[3],
                        'used' => 0,
                        'language' => 'ro'
                    ];
                }

                DB::table('items_list')->insert($insert);
            }
            fclose($handle);
        }

    }
}
