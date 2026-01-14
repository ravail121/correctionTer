<?php

// Include database configuration file
require 'config.php';
require_once 'error_tracker.php';
require_once 'functions.php';

// Connect to the database
$conn = getDbConnection();

// List of indexes to query (File 6)
$indexes = [
    ['name' => 'Nifty 50 (India)', 'symbol' => 'NIFTY50', 'link' => 'https://www.google.com/finance/quote/NIFTY_50:INDEXNSE'],
    ['name' => 'S&P/ASX 200 (Australia)', 'symbol' => 'XJO', 'link' => 'https://www.google.com/finance/quote/XJO:INDEXASX'],
];

$errorTracker = new ErrorTracker($conn, 'insert indexes6');

// Process all indexes
processIndexes($indexes, $conn, $errorTracker);

// Close the database connection
$conn->close();

?>

