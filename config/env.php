<?php
/**
 * Load environment variables from .env.local
 * This file loads local credentials without committing them to GitHub
 */

$envFile = __DIR__ . '/../.env.local';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Set environment variable
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }
}
?>
