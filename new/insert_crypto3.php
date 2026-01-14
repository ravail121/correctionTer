<?php

// Include database configuration file
require 'config.php';
require_once 'error_tracker.php';
require_once 'functions.php';

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// List of cryptocurrencies to query
$cryptocurrencies = [
    ['name' => 'Cardano', 'symbol' => 'ADA'],
    ['name' => 'Bitcoin Cash', 'symbol' => 'BCH'],
    ['name' => 'Hyperliquid', 'symbol' => 'HYPE'],
];

$errorTracker = new ErrorTracker($conn, 'insert crypto3');

// Process all cryptocurrencies
processCryptocurrencies($cryptocurrencies, $conn, $errorTracker);

// Close the database connection
$conn->close();

?>
