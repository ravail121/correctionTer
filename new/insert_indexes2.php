<?php

// Include database configuration file
require 'config.php';
require_once 'error_tracker.php';
require_once 'functions.php';

// Connect to the database
$conn = getDbConnection();

// List of indexes to query (File 2)
$indexes = [
    ['name' => 'Russell 1000', 'symbol' => 'RUI', 'link' => 'https://www.google.com/finance/quote/RUI:INDEXFTSE'],
    ['name' => 'Russell 2000', 'symbol' => 'RUT', 'link' => 'https://www.google.com/finance/quote/RUT:INDEXRUSSELL'],
    ['name' => 'S&P 400 MidCap', 'symbol' => 'MID', 'link' => 'https://www.google.com/finance/quote/MID:INDEXSP'],
    ['name' => 'S&P 600 SmallCap', 'symbol' => 'SML', 'link' => 'https://www.google.com/finance/quote/SML:INDEXSP'],
];

$errorTracker = new ErrorTracker($conn, 'insert indexes2');

// Process all indexes
processIndexes($indexes, $conn, $errorTracker);

// Close the database connection
$conn->close();

?>
