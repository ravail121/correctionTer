<?php

// Include database configuration file
require 'config.php';
require_once 'error_tracker.php';
require_once 'functions.php';

// Connect to the database
$conn = getDbConnection();

// List of indexes to query (File 4)
$indexes = [
    ['name' => 'CAC 40 (France)', 'symbol' => 'FCHI', 'link' => 'https://www.google.com/finance/quote/FCHI:INDEXEURO'],
    ['name' => 'Nikkei 225 (Japan)', 'symbol' => 'N225', 'link' => 'https://www.google.com/finance/quote/N225:INDEXNIKKEI'],
    ['name' => 'TOPIX (Japan)', 'symbol' => 'TOPX', 'link' => 'https://www.google.com/finance/quote/1305:TYO'],
    ['name' => 'Hang Seng Index (Hong Kong)', 'symbol' => 'HSI', 'link' => 'https://www.google.com/finance/quote/HSI:INDEXHANGSENG'],
];

$errorTracker = new ErrorTracker($conn, 'insert indexes4');

// Process all indexes
processIndexes($indexes, $conn, $errorTracker);

// Close the database connection
$conn->close();

?>
