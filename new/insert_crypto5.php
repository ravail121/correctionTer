<?php

require 'config.php';
require_once 'error_tracker.php';
require_once 'functions.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cryptocurrencies = [
    ['name' => 'Stellar', 'symbol' => 'XLM'],
    ['name' => 'Monero', 'symbol' => 'XMR'],
    ['name' => 'Hedera', 'symbol' => 'HBAR'],
];

$errorTracker = new ErrorTracker($conn, 'insert crypto5');

// Process all cryptocurrencies
processCryptocurrencies($cryptocurrencies, $conn, $errorTracker);

$conn->close();

?>

