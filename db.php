<?php
// config.php

// Load environment variables (you can use libraries like vlucas/phpdotenv)
$stripe_secret_key = getenv('sk_test_51QByfJE4KNNCb6nuElXbMZUUan5s9fkJ1N2Ce3fMunhTipH5LGonlnO3bcq6eaxXINmWDuMzfw7RFTNTOb1jDsEm00IzfwoFx2');
$stripe_publishable_key = getenv('pk_test_51QByfJE4KNNCb6nuSnWLZP9JXlW84zG9DnOrQDTHQJvus9D8A8vOA85S4DfRlyWgN0rxa2hHzjppchnrmhyZGflx00B2kKlxym');

$env = 'local'; // Default to 'local' if not set
if ($env === 'local') {
     $host = 'localhost';
     $db   = 'yumiis_e';
     $user = 'root';
     $pass = '';
} else {
     $host = '127.0.0.1';
     $db   = 'yumiis_e';
     $user = 'ab';
     $pass = 'bUd!d1107';
}

$charset = 'utf8mb4';
$port = 8443;

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
