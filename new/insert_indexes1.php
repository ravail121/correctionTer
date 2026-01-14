<?php

// Include database configuration file
require 'config.php';
require_once 'error_tracker.php';
require_once 'functions.php';

// Connect to the database
$conn = getDbConnection();

// List of indexes to query (File 1)
$indexes = [
    // ['name' => 'S&P 500', 'symbol' => 'SPX', 'link' => 'https://www.google.com/finance/quote/.INX:INDEXSP'],
    ['name' => 'NASDAQ-100', 'symbol' => 'NDX', 'link' => 'https://www.google.com/finance/quote/NDX:INDEXNASDAQ']
    // ['name' => 'NASDAQ Composite', 'symbol' => 'IXIC', 'link' => 'https://www.google.com/finance/quote/IXIC:NASDAQ'],
    // ['name' => 'Dow Jones Industrial Average', 'symbol' => 'DJI', 'link' => 'https://www.google.com/finance/quote/.DJI:INDEXDJX'],
];

$errorTracker = new ErrorTracker($conn, 'insert indexes1');

// Process all indexes
processIndexes($indexes, $conn, $errorTracker);

// Close the database connection
$conn->close();

?>

