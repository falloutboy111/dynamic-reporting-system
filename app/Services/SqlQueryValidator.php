<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

class SqlQueryValidator
{
    /**
     * Validate that the SQL query is read-only (SELECT only)
     */
    public function validate(string $query): array
    {
        $query = trim($query);
        
        // Remove comments
        $query = preg_replace('/--.*$/m', '', $query);
        $query = preg_replace('/\/\*.*?\*\//s', '', $query);
        
        // Check if empty
        if (empty($query)) {
            return [
                'valid' => false,
                'error' => 'Query cannot be empty',
            ];
        }

        // Convert to uppercase for checking
        $upperQuery = strtoupper($query);

        // Must start with SELECT
        if (!preg_match('/^\s*SELECT\s/i', $query)) {
            return [
                'valid' => false,
                'error' => 'Only SELECT queries are allowed',
            ];
        }

        // Block dangerous keywords
        $dangerousKeywords = [
            'INSERT',
            'UPDATE',
            'DELETE',
            'DROP',
            'CREATE',
            'ALTER',
            'TRUNCATE',
            'GRANT',
            'REVOKE',
            'EXEC',
            'EXECUTE',
            'CALL',
            'PREPARE',
            'DEALLOCATE',
            'LOCK',
            'UNLOCK',
            'RENAME',
            'LOAD DATA',
            'INTO OUTFILE',
            'INTO DUMPFILE',
        ];

        foreach ($dangerousKeywords as $keyword) {
            if (strpos($upperQuery, $keyword) !== false) {
                return [
                    'valid' => false,
                    'error' => "Query contains invalid operation: {$keyword}",
                ];
            }
        }

        // Additional security checks
        if (preg_match('/;\s*SELECT/i', $query)) {
            return [
                'valid' => false,
                'error' => 'Multiple queries are not allowed',
            ];
        }

        return [
            'valid' => true,
            'error' => null,
        ];
    }

    /**
     * Sanitize query (basic sanitization, parameterized queries should still be used)
     */
    public function sanitize(string $query): string
    {
        $query = trim($query);
        
        // Remove comments
        $query = preg_replace('/--.*$/m', '', $query);
        $query = preg_replace('/\/\*.*?\*\//s', '', $query);
        
        return $query;
    }
}

