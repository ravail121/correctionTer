<?php

// Include database configuration file
require 'config.php';
require_once 'error_tracker.php';
require_once 'functions.php';

// Connect to the database
$conn = getDbConnection();

// List of indexes to query (File 3)
$indexes = [
    ['name' => 'FTSE 100 (UK)', 'symbol' => 'FTSE', 'link' => 'https://www.google.com/finance/quote/UKX:INDEXFTSE'],
    ['name' => 'Euro Stoxx 50', 'symbol' => 'STOXX50E', 'link' => 'https://www.google.com/finance/quote/SX5E:INDEXSTOXX'],
    ['name' => 'DAX (Germany)', 'symbol' => 'DAX', 'link' => 'https://www.google.com/finance/quote/DAX:INDEXDB'],
];

$errorTracker = new ErrorTracker($conn, 'insert indexes3');

// Process all indexes
processIndexes($indexes, $conn, $errorTracker);

// Close the database connection
$conn->close();

?>
