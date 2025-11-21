<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Organisation;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Exception;

class DatabaseConnectionManager
{
    /**
     * Create a dynamic database connection for an organisation
     */
    public function createConnection(Organisation $organisation): bool
    {
        $connectionName = "org_{$organisation->id}";

        try {
            Config::set("database.connections.{$connectionName}", [
                'driver' => 'mysql',
                'host' => $organisation->database_host,
                'port' => $organisation->database_port,
                'database' => $organisation->database_name,
                'username' => $organisation->database_username,
                'password' => $organisation->database_password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]);

            // Test the connection
            DB::connection($connectionName)->getPdo();

            return true;
        } catch (Exception $e) {
            \Log::error("Failed to create database connection for organisation {$organisation->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Test database connection without saving
     */
    public function testConnection(
        string $host,
        string $database,
        string $username,
        string $password,
        int $port = 3306
    ): array {
        $testConnectionName = 'test_connection_' . uniqid();

        try {
            Config::set("database.connections.{$testConnectionName}", [
                'driver' => 'mysql',
                'host' => $host,
                'port' => $port,
                'database' => $database,
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]);

            DB::connection($testConnectionName)->getPdo();
            DB::purge($testConnectionName);

            return [
                'success' => true,
                'message' => 'Connection successful',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get the connection name for an organisation
     */
    public function getConnectionName(Organisation $organisation): string
    {
        return "org_{$organisation->id}";
    }

    /**
     * Close and purge connection
     */
    public function closeConnection(Organisation $organisation): void
    {
        $connectionName = $this->getConnectionName($organisation);
        DB::purge($connectionName);
    }
}

