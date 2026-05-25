
<?php
require 'config.php';

function db(): PDO {
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;

    // Validate required constants
    foreach (['DB_HOST','DB_NAME','DB_USER','DB_SSLMODE'] as $k) {
        if (!defined($k) || constant($k) === '') {
            throw new RuntimeException("Config error: missing or empty {$k}");
        }
    }
    if (!defined('DB_PORT') || (int)DB_PORT <= 0) {
        throw new RuntimeException("Config error: DB_PORT must be a positive integer");
    }

    $dsn = sprintf(
        'pgsql:host=%s;port=%d;dbname=%s;sslmode=%s',
        DB_HOST,
        (int)DB_PORT,
        DB_NAME,
        DB_SSLMODE
    );

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // PDO::ATTR_PERSISTENT      => true,
        ]);
        // Optional: cap statement time to 10s
        //$pdo->exec("SET statement_timeout TO 10000");
    } catch (Throwable $e) {
        // Emit a concise but informative error (visible in test_db.php)
        throw new RuntimeException("DB connect failed: " . $e->getMessage());
    }

    return $pdo;
}
?>
