<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database {--path= : Custom output directory}';
    protected $description = 'Create a timestamped MySQL dump of the configured database.';

    public function handle(): int
    {
        $connection = Config::get('database.default');
        $config = Config::get("database.connections.{$connection}");

        if (($config['driver'] ?? null) !== 'mysql') {
            $this->error('This command only supports MySQL connections.');
            return self::FAILURE;
        }

        $host = $config['host'] ?? '127.0.0.1';
        $port = $config['port'] ?? 3306;
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'] ?? '';

        $directory = $this->option('path') ?: storage_path('app/backups');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = $database . '_' . now()->format('Y-m-d_His') . '.sql';
        $outputPath = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        $mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
        if (!file_exists($mysqldump)) {
            $mysqldump = 'mysqldump';
        }

        $command = sprintf(
            '"%s" --host=%s --port=%s --user=%s %s %s > "%s"',
            $mysqldump,
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            $password !== '' ? '--password=' . escapeshellarg($password) : '',
            escapeshellarg($database),
            $outputPath
        );

        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            $this->error('Database backup failed.');
            return self::FAILURE;
        }

        $this->info("Backup created: {$outputPath}");

        return self::SUCCESS;
    }
}