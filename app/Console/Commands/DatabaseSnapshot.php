<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DatabaseSnapshot extends Command
{
    protected $signature = 'db:snapshot
                            {--gzip : Compress the snapshot}
                            {--path= : Directory to write the snapshot to}
                            {--binary= : mysqldump binary (defaults to DB_DUMP_BINARY or mysqldump)}';

    protected $description = 'Create a MySQL database snapshot for delivery or backup';

    public function handle(): int
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}", []);

        if (($config['driver'] ?? null) !== 'mysql') {
            $this->error('db:snapshot only supports the MySQL driver.');

            return self::FAILURE;
        }

        $directory = $this->option('path') ?: storage_path('app/backups');
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            $this->error('Could not create directory: '.$directory);

            return self::FAILURE;
        }

        $database = $config['database'] ?? 'database';
        $sqlPath = rtrim($directory, '/\\').DIRECTORY_SEPARATOR.$database.'-'.now()->format('Ymd-His').'.sql';
        $binary = $this->option('binary') ?: env('DB_DUMP_BINARY', 'mysqldump');

        $command = [
            $binary,
            '--host='.($config['host'] ?? '127.0.0.1'),
            '--port='.($config['port'] ?? '3306'),
            '--user='.($config['username'] ?? 'root'),
            '--single-transaction',
            '--skip-lock-tables',
            '--routines',
            $database,
        ];

        try {
            $process = new Process($command, null, [
                'MYSQL_PWD' => (string) ($config['password'] ?? ''),
            ]);

            $process->setTimeout(600);
            $process->run();

            if (! $process->isSuccessful()) {
                $this->error('mysqldump failed: '.trim($process->getErrorOutput() ?: $process->getOutput()));

                return self::FAILURE;
            }

            file_put_contents($sqlPath, $process->getOutput());
        } catch (\Throwable $e) {
            $this->error('Snapshot failed: '.$e->getMessage());
            $this->line('Set DB_DUMP_BINARY or pass --binary to point at mysqldump.');

            return self::FAILURE;
        }

        if ($this->option('gzip')) {
            $gzPath = $sqlPath.'.gz';
            $source = fopen($sqlPath, 'rb');
            $target = gzopen($gzPath, 'wb9');

            if ($source === false || $target === false) {
                $this->error('Could not compress the snapshot.');

                return self::FAILURE;
            }

            while (! feof($source)) {
                gzwrite($target, fread($source, 1048576));
            }

            fclose($source);
            gzclose($target);
            unlink($sqlPath);

            $sqlPath = $gzPath;
        }

        $this->info('Database snapshot written to '.$sqlPath);

        return self::SUCCESS;
    }
}
