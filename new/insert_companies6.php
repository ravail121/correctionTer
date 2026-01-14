<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'error_tracker.php';

$date = getTradingDate();

// $companies = [
//     'META' => 'Meta Platforms Inc.',
//     'NVDA' => 'NVIDIA Corporation',
//     'AMZN' => 'Amazon.com Inc.',
//     'GOOGL' => 'Alphabet Inc.',
//     'MSFT' => 'Microsoft Corporation'
// ];

$con = getDbConnection();
$errorTracker = new ErrorTracker($con, __FILE__);
$companies = getCompaniesChunk($con, 5);

processCompanies($companies, $con, $errorTracker, $date);

// Errors are now saved to database only - no email sending from stock scripts
// Email will be sent by nightly script (send_stock_error_email.php)

$con->close();
?>
