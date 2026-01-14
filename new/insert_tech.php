<?php
require_once 'config.php';
require_once 'functions.php';
$date=date('Y-m-d');
$companies = [  
    'TECH' => 'Bio-Techne'
];


$con = getDbConnection();

foreach ($companies as $symbol => $name) {
    try {
        // Fetch last close price
         // First API call to get last close price for a specific date
        $lastCloseUrl = "https://api.polygon.io/v2/aggs/ticker/$symbol/range/1/day/$date/$date?apiKey=" . API_KEY;
        $responseLastClose = file_get_contents($lastCloseUrl);
        if ($responseLastClose === false) {
            throw new Exception("Error fetching last close data for $symbol on $date");
        }
        $lastCloseData = json_decode($responseLastClose, true);
        $lastClosePrice = $lastCloseData['results'][0]['c'] ?? null;

        // If last close price is null, make a second API call to get previous close price
        if ($lastClosePrice === null) {
            $lastCloseUrl = "https://api.polygon.io/v2/aggs/ticker/$symbol/prev?adjusted=true&apiKey=" . API_KEY;
            $responseLastClose = file_get_contents($lastCloseUrl);
            if ($responseLastClose === false) {
                throw new Exception("Error fetching previous close data for $symbol");
            }
            $lastCloseData = json_decode($responseLastClose, true);
            $lastClosePrice = $lastCloseData['results'][0]['c'] ?? null;
        }

        if ($lastClosePrice === null) {
            throw new Exception("Unable to retrieve last close price for $symbol");
        }

        // Fetch all-time high
        $allTimeHighUrl = "https://api.polygon.io/v2/aggs/ticker/$symbol/range/1/day/2010-01-01/2024-12-31?adjusted=true&apiKey=" . API_KEY;
        $responseAllTimeHigh = file_get_contents($allTimeHighUrl);
        if ($responseAllTimeHigh === false) {
            throw new Exception("Error fetching all time high data for $symbol");
        }
        $allTimeHighData = json_decode($responseAllTimeHigh, true);
        $allTimeHighPrice =134.72;

        // Calculate correction range
        $correctionRange = ($lastClosePrice - $allTimeHighPrice) / $allTimeHighPrice * 100;
        $correctionRangeFormatted = formatCorrectionRange($correctionRange);

        // Fetch market cap
        $companyUrl = "https://api.polygon.io/v3/reference/tickers/$symbol?apiKey=" . API_KEY;
        $responseCompany = file_get_contents($companyUrl);
        if ($responseCompany === false) {
            throw new Exception("Error fetching company data for $symbol");
        }
        $companyData = json_decode($responseCompany, true);
        $marketCap = $companyData['results']['market_cap'] ?? null;

        // Fetch average volume for May 2024
        $volumeUrl = "https://api.polygon.io/v2/aggs/ticker/$symbol/range/1/day/2024-05-01/2024-05-31?adjusted=true&apiKey=" . API_KEY;
        $responseVolume = file_get_contents($volumeUrl);
        if ($responseVolume === false) {
            throw new Exception("Error fetching volume data for $symbol");
        }
        $volumeData = json_decode($responseVolume, true);
        $averageVolume = array_sum(array_column($volumeData['results'], 'v')) / count($volumeData['results']);

        // Attempt to update existing record; if no rows affected, then insert new record
        $dateTime = date('Y-m-d H:i:s');
        $updateStmt = $con->prepare("UPDATE companies SET last_close=?, all_time_high=?, market_cap=?, average_volume=?, correction_range=?, date_time=? WHERE symbl=?");
        if (!$updateStmt) {
            throw new Exception("Prepare statement failed: " . $con->error);
        }
        $updateStmt->bind_param("dddidss", $lastClosePrice, $allTimeHighPrice, $marketCap, $averageVolume, $correctionRangeFormatted, $dateTime, $symbol);
        if (!$updateStmt->execute()) {
            // If no rows updated (record doesn't exist), insert new record
            if ($updateStmt->errno === 0 && $updateStmt->affected_rows === 0) {
                $insertStmt = $con->prepare("INSERT INTO companies (symbl, name, last_close, all_time_high, market_cap, average_volume, correction_range, date_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if (!$insertStmt) {
                    throw new Exception("Prepare statement failed: " . $con->error);
                }
                $insertStmt->bind_param("ssdddids", $symbol, $name, $lastClosePrice, $allTimeHighPrice, $marketCap, $averageVolume, $correctionRangeFormatted, $dateTime);
                if (!$insertStmt->execute()) {
                    throw new Exception("Execute failed: " . $insertStmt->error);
                }
                echo "Record for $name inserted successfully.\n";
                $insertStmt->close();
            } else {
                throw new Exception("Execute failed: " . $updateStmt->error);
            }
        } else {
            echo "Record for $name updated successfully.\n";
        }
        $updateStmt->close();
    } catch (Exception $e) {
        echo "Error processing $name: " . $e->getMessage() . "\n";
    }
}

$con->close();

// Function to format correction range
function formatCorrectionRange($range) {
    return ($range >= 0) ? '+' . number_format($range, 2) . '%' : number_format($range, 2) . '%';
}
?>
