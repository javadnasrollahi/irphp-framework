<?php
namespace App\Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class Migrator
{
    protected string $path;
    protected string $table = 'migrations';

    public function __construct(?string $path = null)
    {
        $this->path = $path ?? base_path('database/migrations');
        $this->ensureMigrationsTable();
    }

    protected function ensureMigrationsTable(): void
    {
        $schema = Capsule::schema();
        if (! $schema->hasTable($this->table)) {
            $schema->create($this->table, function ($table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });
        }
    }

    protected function ran(): array
    {
        return Capsule::table($this->table)->pluck('migration')->all();
    }

    protected function nextBatch(): int
    {
        $max = Capsule::table($this->table)->max('batch');
        return ((int) $max) + 1;
    }

    public function migrate(): array
    {
        $ran     = $this->ran();
        $batch   = $this->nextBatch();
        $applied = [];

        foreach ($this->files() as $file => $className) {
            if (in_array($file, $ran, true)) {
                continue;
            }

            require_once $this->path . '/' . $file . '.php';
            $migration = new $className();
            $migration->up();

            Capsule::table($this->table)->insert([
                'migration' => $file,
                'batch'     => $batch,
            ]);

            $applied[] = $file;
        }

        return $applied;
    }

    public function rollback(): array
    {
        $lastBatch = Capsule::table($this->table)->max('batch');
        if (! $lastBatch) {
            return [];
        }

        $rows     = Capsule::table($this->table)->where('batch', $lastBatch)->get();
        $reverted = [];
        $classMap = $this->files();

        foreach ($rows as $row) {
            $file      = $row->migration;
            $className = $classMap[$file] ?? null;

            if ($className) {
                require_once $this->path . '/' . $file . '.php';
                (new $className())->down();
            }

            Capsule::table($this->table)->where('migration', $file)->delete();
            $reverted[] = $file;
        }

        return $reverted;
    }

    protected function files(): array
    {
        $map = [];
        foreach (glob($this->path . '/*.php') ?: [] as $filePath) {
            $file  = basename($filePath, '.php');
            $parts = explode('_', $file, 5);
            // نام کلاس بعد از الگوی timestamp_ (مثل create_users_table)
            $className  = str_replace(' ', '', ucwords(str_replace('_', ' ', preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $file))));
            $map[$file] = $className;
        }
        return $map;
    }
}
