<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupTablesSQL extends Command
{
    protected $signature = 'backup:tables';
    protected $description = 'Backup specific tables as SQL files and store in storage/daily';

    public function handle()
    {
        $tables = [
            'go_down_products',
            'products',
            'categories',
            'orders',
            'order_details',
            'go_down_product_details',
            'damaged_products_details',
            'product_vendors',
            'customers',
            'customer_addresses'
        ];

        $today = date('Y-m-d');
        $backupPath = storage_path('daily/' . $today);

        // Create today's folder if not exists
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        // Backup tables
        foreach ($tables as $table) {
            $filename = $backupPath . '/' . $table . '_' . $today . '.sql';
            $this->info("Backing up table: $table");

            // Get CREATE TABLE statement
            $createTable = DB::select("SHOW CREATE TABLE `$table`")[0]->{'Create Table'};

            // Get all data
            $rows = DB::table($table)->get();

            // Build SQL dump
            $sqlDump = "DROP TABLE IF EXISTS `$table`;\n";
            $sqlDump .= $createTable . ";\n\n";

            foreach ($rows as $row) {
                $columns = array_map(fn($col) => "`$col`", array_keys((array)$row));
                $values = array_map(function ($val) {
                    return $val === null ? 'NULL' : "'" . addslashes($val) . "'";
                }, array_values((array)$row));

                $sqlDump .= "INSERT INTO `$table` (" . implode(',', $columns) . ") VALUES (" . implode(',', $values) . ");\n";
            }

            File::put($filename, $sqlDump);
        }

        $this->info("Backup completed. Files stored in: $backupPath");

        $this->deleteOldBackups();
    }

    protected function deleteOldBackups()
    {
        $dailyFolder = storage_path('daily');
        if (!File::exists($dailyFolder)) return;

        $folders = File::directories($dailyFolder);

        foreach ($folders as $folder) {
            $folderDate = basename($folder);

            // If folder date is older than 15 days, delete it
            if (Carbon::parse($folderDate)->lt(Carbon::now()->subDays(15))) {
                File::deleteDirectory($folder);
                $this->info("Deleted old backup folder: $folderDate");
            }
        }
    }
}
