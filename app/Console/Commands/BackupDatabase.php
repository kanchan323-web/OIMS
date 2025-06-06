<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the MySQL database and store it in storage/app/backups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', 3306);
        $backupPath = storage_path('app/backups');

        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $timestamp = now()->format('Ymd_His');
        $fileName = "{$database}_{$timestamp}.sql";
        $filePath = "{$backupPath}/{$fileName}";

        // Build mysqldump command
        $command = "\"C:\\xampp\\mysql\\bin\\mysqldump.exe\" -h {$host} -P {$port} -u {$username}";
        if (!empty($password)) {
            $command .= " -p{$password}";
        }
        $command .= " {$database} > \"{$filePath}\"";

        // Run command
        exec($command, $output, $return);

        if ($return === 0) {
            $this->info("✅ Backup successful: {$filePath}");
        } else {
            $this->error("❌ Backup failed with exit code: {$return}");
        }

    }
}
