<?php

// Include database configuration file
require 'config.php';
require_once 'error_tracker.php';
require_once 'functions.php';

// Connect to the database
$conn = getDbConnection();

// List of indexes to query (File 5)
$indexes = [
    ['name' => 'CSI 300 (China)', 'symbol' => 'CSI300', 'link' => 'https://www.google.com/finance/quote/3188:HK'],
    ['name' => 'Shanghai Composite', 'symbol' => 'SSEC', 'link' => 'https://www.google.com/finance/quote/000001:SHA'],
    ['name' => 'S&P/TSX Composite (Canada)', 'symbol' => 'GSPTSE', 'link' => 'https://www.google.com/finance/quote/GSPTSE:INDEXTSI'],
];

$errorTracker = new ErrorTracker($conn, 'insert indexes5');

// Process all indexes
processIndexes($indexes, $conn, $errorTracker);

// Close the database connection
$conn->close();

?>
