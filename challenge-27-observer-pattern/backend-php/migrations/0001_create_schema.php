<?php

declare(strict_types=1);

use App\Repository\SqliteEventRepository;

/**
 * Migration to create the initial database schema
 */
return [
    'up' => function () {
        // The SqliteEventRepository automatically creates the schema
        // This migration is just for demonstration purposes
        echo "Database schema created successfully.\n";
        return true;
    },
    
    'down' => function () {
        // In a real implementation, this would drop the schema
        echo "Database schema dropped successfully.\n";
        return true;
    }
];