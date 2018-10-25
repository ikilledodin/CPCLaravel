<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class HealthQuestRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add users roles to the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $CSVFile = public_path('roles.csv');
        if(!file_exists($CSVFile) || !is_readable($CSVFile))
            return false;

        $header = null;
        $data = array();

        if (($handle = fopen($CSVFile,'r')) !== false){
            while (($row = fgetcsv($handle, 1000, ',')) !==false){
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        $dataCount = count($data);
        // for ($i = 0; $i < $dataCount; $i ++){
        //     Product::firstOrCreate($data[$i]);
        // }
        foreach($data as $role) {
            if(!empty($role['Name'])) {
                echo "Role: ". $role['Name'] . "\n";
                Role::create(['name' => $role['Name']]);
            }
        }
        echo "Roles above were added successfully"."\n";
    }
}
