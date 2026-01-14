<?php

$con = new mysqli('localhost', 'corrgltt_companies', '?%!!mZ5HP^#-', 'corrgltt_companies');
date_default_timezone_set('America/New_York');
$currentDateTime = date('D M d Y h:i:s A');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// ===============================
// API NINJAS CONFIG
// ===============================
$apiKey = 'Gb/PJPyzgP9n9C1pS0HUYA==IKtTqYJHOMfVjAP9'; // keep this private
$apiUrl = 'https://api.api-ninjas.com/v1/sp500';

// ===============================
// CALL API
// ===============================
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Api-Key: ' . $apiKey
]);

$response = curl_exec($ch);

if ($response === false) {
    die('API Error: ' . curl_error($ch));
}

curl_close($ch);

$data = json_decode($response, true);

if (!is_array($data)) {
    die('Invalid API response');
}

// ===============================
// DELETE OLD DATA
// ===============================
$deleteQuery = "DELETE FROM sp500";
if (!$con->query($deleteQuery)) {
    die("Error deleting old data: " . $con->error);
}

// ===============================
// INSERT NEW DATA
// ===============================
$inserted = 0;
// print_r($data);exit;
foreach ($data as $row) {

    if (empty($row['ticker']) || empty($row['company_name'])) {
        continue;
    }

    $symbol = $con->real_escape_string($row['ticker']);
    $company_name = $con->real_escape_string($row['company_name']);

    $insertQuery = "
        INSERT INTO sp500 (symbol, company_name)
        VALUES ('$symbol', '$company_name')
    ";

    if ($con->query($insertQuery)) {
        $inserted++;
    }
}

echo "S&P 500 sync completed. Rows inserted: " . $inserted . "\n";

// ===============================
// DELETE COMPANIES NOT IN S&P 500
// ===============================
// Get all S&P 500 symbols
$sp500SymbolsQuery = "SELECT symbol FROM sp500";
$sp500Result = $con->query($sp500SymbolsQuery);

if (!$sp500Result) {
    die("Error fetching S&P 500 symbols: " . $con->error);
}

// Build array of S&P 500 symbols
$sp500Symbols = [];
while ($row = $sp500Result->fetch_assoc()) {
    $sp500Symbols[] = $con->real_escape_string($row['symbol']);
}

if (empty($sp500Symbols)) {
    echo "WARNING: No S&P 500 symbols found. Skipping company cleanup.\n";
} else {
    // Create placeholders for IN clause
    $placeholders = str_repeat('?,', count($sp500Symbols) - 1) . '?';
    
    // Delete companies that are NOT in the S&P 500 list
    $deleteQuery = "DELETE FROM companies WHERE symbl NOT IN ($placeholders)";
    $stmt = $con->prepare($deleteQuery);
    
    if ($stmt) {
        // Bind parameters - need to create type string and bind all symbols
        $types = str_repeat('s', count($sp500Symbols));
        $stmt->bind_param($types, ...$sp500Symbols);
        
        if ($stmt->execute()) {
            $deleted = $stmt->affected_rows;
            echo "Cleanup completed. Companies deleted (not in S&P 500): " . $deleted . "\n";
        } else {
            echo "ERROR: Failed to delete companies: " . $stmt->error . "\n";
        }
        $stmt->close();
    } else {
        // Fallback to string-based query if prepared statement fails
        $symbolsList = "'" . implode("','", $sp500Symbols) . "'";
        $deleteQuery = "DELETE FROM companies WHERE symbl NOT IN ($symbolsList)";
        
        if ($con->query($deleteQuery)) {
            $deleted = $con->affected_rows;
            echo "Cleanup completed. Companies deleted (not in S&P 500): " . $deleted . "\n";
        } else {
            echo "ERROR: Failed to delete companies: " . $con->error . "\n";
        }
    }
}

$con->close();
?>
