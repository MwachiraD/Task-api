#!/usr/bin/env sh
set -eu

php -r '
$host = getenv("DB_HOST");
$port = (int) (getenv("DB_PORT") ?: 3306);

if (!$host) {
    fwrite(STDERR, "DB_HOST is not set.\n");
    exit(1);
}

for ($attempt = 1; $attempt <= 60; $attempt++) {
    $connection = @fsockopen($host, $port, $errno, $errstr, 2);

    if ($connection) {
        fclose($connection);
        exit(0);
    }

    sleep(2);
}

fwrite(STDERR, "Database is not reachable at {$host}:{$port}.\n");
exit(1);
'

php artisan migrate --force --seed
