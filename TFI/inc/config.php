<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


// set to true temporarily to verify everything works
$ENV_DEBUG = false;

// set to true to test DB connection immediately
$ENV_TEST_DB = false;

/* ==========================
   LOAD .env
   ========================== */

$envPath = __DIR__ . '/../.env'; 
if (!is_file($envPath)) {
    throw new RuntimeException(".env not found at: $envPath");
}

$env = parse_ini_file($envPath, false, INI_SCANNER_TYPED);

if (!is_array($env)) {
    throw new RuntimeException("Failed to parse .env at: $envPath");
}

/* ==========================
   HELPERS
   ========================== */

$req = function (string $key) use ($env, $envPath): string {
    if (!array_key_exists($key, $env) || $env[$key] === null || $env[$key] === '') {
        throw new RuntimeException("Missing required key '$key' in $envPath");
    }
    return trim((string)$env[$key]);
};

$opt = function (string $key, $default) use ($env) {
    return array_key_exists($key, $env)
        ? (is_string($env[$key]) ? trim($env[$key]) : $env[$key])
        : $default;
};

/* ==========================
   DB PROFILE SELECTION (ADDED)
   ========================== */

// Default behaviour = existing DB_* keys
$DB_PROFILE = defined('DB_PROFILE') ? DB_PROFILE : 'LOCAL';

// Prefix map
$DB_PREFIX = match ($DB_PROFILE) {
    'RA'     => 'RA_DB_',
    'LOCAL'  => 'DB_',
    default  => throw new RuntimeException("Invalid DB_PROFILE: $DB_PROFILE")
};

/* ==========================
   DEFINE CONSTANTS
   ========================== */

// App
define('APP_URL', $opt('APP_URL', ''));

// Database (REQUIRED)
// ONLY CHANGE IS PREFIXED LOOKUP
define('DB_HOST',    $req($DB_PREFIX . 'HOST'));
define('DB_PORT',    (int)$opt($DB_PREFIX . 'PORT', 5432));
define('DB_NAME',    $req($DB_PREFIX . 'NAME'));
define('DB_USER',    $req($DB_PREFIX . 'USER'));
define('DB_PASS',    $req($DB_PREFIX . 'PASS'));
define('DB_SSLMODE', $opt('DB_SSLMODE', 'disable')); // unchanged

// Session
define('SESSION_NAME_APP', $opt('SESSION_NAME', 'brue_appsid'));

/* ==========================
   DEBUG OUTPUT (OPTIONAL)
   ========================== */

if ($ENV_DEBUG) {
    echo "<pre>";
    echo "ENV LOADED OK\n";
    echo "DB_PROFILE = $DB_PROFILE\n";
    echo "DB_HOST    = " . DB_HOST . "\n";
    echo "DB_PORT    = " . DB_PORT . "\n";
    echo "DB_NAME    = " . DB_NAME . "\n";
    echo "DB_USER    = " . DB_USER . "\n";
    echo "DB_SSLMODE = " . DB_SSLMODE . "\n";
    echo "</pre>";
}

/* ==========================
   DB CONNECTION TEST (OPTIONAL)
   ========================== */

if ($ENV_TEST_DB) {

    $connStr =
        "host=" . DB_HOST .
        " port=" . DB_PORT .
        " dbname=" . DB_NAME .
        " user=" . DB_USER .
        " password=" . DB_PASS .
        " sslmode=" . DB_SSLMODE;

    $con = pg_connect($connStr);

    if (!$con) {
        throw new RuntimeException('DB connection failed: ' . pg_last_error());
    }

    if ($ENV_DEBUG) {
        echo "<pre>DB CONNECT OK</pre>";
    }

    pg_close($con);
}
?>
