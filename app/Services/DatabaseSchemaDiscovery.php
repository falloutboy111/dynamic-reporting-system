<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Organisation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Exception;

class DatabaseSchemaDiscovery
{
    protected DatabaseConnectionManager $connectionManager;

    public function __construct(DatabaseConnectionManager $connectionManager)
    {
        $this->connectionManager = $connectionManager;
    }

    /**
     * Get database schema (tables and columns) for an organisation
     */
    public function getSchema(Organisation $organisation, bool $fresh = false): array
    {
        $cacheKey = "db_schema_{$organisation->id}";

        if (!$fresh && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            // Create connection
            $this->connectionManager->createConnection($organisation);
            $connectionName = $this->connectionManager->getConnectionName($organisation);

            // Get all tables
            $tables = DB::connection($connectionName)
                ->select("SHOW TABLES");

            $schema = [];
            $databaseName = $organisation->database_name;
            $tableKey = "Tables_in_{$databaseName}";

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;

                // Get columns for each table
                $columns = DB::connection($connectionName)
                    ->select("SHOW COLUMNS FROM `{$tableName}`");

                $columnData = [];
                foreach ($columns as $column) {
                    $columnData[] = [
                        'name' => $column->Field,
                        'type' => $column->Type,
                        'null' => $column->Null === 'YES',
                        'key' => $column->Key,
                        'default' => $column->Default,
                        'extra' => $column->Extra,
                    ];
                }

                // Get foreign keys
                $foreignKeys = $this->getForeignKeys($connectionName, $tableName);

                $schema[] = [
                    'name' => $tableName,
                    'columns' => $columnData,
                    'relationships' => $foreignKeys,
                ];
            }

            // Cache for 1 hour
            Cache::put($cacheKey, $schema, 3600);

            return $schema;
        } catch (Exception $e) {
            \Log::error("Failed to discover schema for organisation {$organisation->id}: " . $e->getMessage());
            return [];
        } finally {
            $this->connectionManager->closeConnection($organisation);
        }
    }

    /**
     * Get foreign key relationships for a table
     */
    protected function getForeignKeys(string $connectionName, string $tableName): array
    {
        try {
            $foreignKeys = DB::connection($connectionName)
                ->select("
                    SELECT 
                        COLUMN_NAME,
                        REFERENCED_TABLE_NAME,
                        REFERENCED_COLUMN_NAME
                    FROM
                        information_schema.KEY_COLUMN_USAGE
                    WHERE
                        TABLE_SCHEMA = DATABASE()
                        AND TABLE_NAME = ?
                        AND REFERENCED_TABLE_NAME IS NOT NULL
                ", [$tableName]);

            $relationships = [];
            foreach ($foreignKeys as $fk) {
                $relationships[] = [
                    'column' => $fk->COLUMN_NAME,
                    'referenced_table' => $fk->REFERENCED_TABLE_NAME,
                    'referenced_column' => $fk->REFERENCED_COLUMN_NAME,
                ];
            }

            return $relationships;
        } catch (Exception $e) {
            \Log::error("Failed to get foreign keys for table {$tableName}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Clear cached schema for an organisation
     */
    public function clearCache(Organisation $organisation): void
    {
        Cache::forget("db_schema_{$organisation->id}");
    }
}

