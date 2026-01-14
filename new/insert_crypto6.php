<?php

require 'config.php';
require_once 'error_tracker.php';
require_once 'functions.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cryptocurrencies = [
    ['name' => 'Litecoin', 'symbol' => 'LTC'],
    ['name' => 'Avalanche', 'symbol' => 'AVAX'],
    ['name' => 'Sui', 'symbol' => 'SUI'],
    ['name' => 'Polkadot', 'symbol' => 'DOT'],
];

$errorTracker = new ErrorTracker($conn, 'insert crypto6');

// Process all cryptocurrencies
processCryptocurrencies($cryptocurrencies, $conn, $errorTracker);

$conn->close();

?>

