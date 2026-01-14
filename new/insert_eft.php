<?php
require_once 'config.php';
require_once 'functions.php';


$companies = [
    'XLK' => 'Technology Select Sector SPDR Fund',
    'XLF' => 'Financial Select Sector SPDR Fund',
    'XLV' => 'Health Care Select Sector SPDR Fund',
    'XLY' => 'Consumer Discretionary Select Sector SPDR Fund',
    'XLI' => 'Industrial Select Sector SPDR Fund',
    'XLE' => 'Energy Select Sector SPDR Fund',
    'XLU' => 'Utilities Select Sector SPDR Fund',
    'XLP' => 'Consumer Staples Select Sector SPDR Fund',
    'XLRE' => 'Real Estate Select Sector SPDR Fund',
    'XLB' => 'Materials Select Sector SPDR Fund',
    'XLC' => 'Communication Services Select Sector SPDR Fund',
    'SOXX' => 'iShares Semiconductor ETF',
    'IBB' => 'iShares Nasdaq Biotechnology ETF',
    'ITA' => 'iShares U.S. Aerospace & Defense ETF',
    'PAVE' => 'Global X U.S. Infrastructure Development ETF',
    'ICLN' => 'iShares Global Clean Energy ETF',
    'IHE' => 'iShares U.S. Pharmaceuticals ETF',
    'XME' => 'SPDR S&P Metals and Mining ETF',
    'XRT' => 'SPDR S&P Retail ETF',
    'IYT' => 'iShares Transportation Average ETF',
    'QQQ' => 'Nasdaq 100 ETF',
    'SPY' => 'S&P 500 ETF',
    'TA125' => 'TA-125 ETF',
    'DIA' => 'SPDR Dow Jones Industrial Average ETF Trust'
];

$con = getDbConnection();

foreach ($companies as $symbol => $name) {
    try {
        // Fetch last close price
        $lastCloseUrl = "https://api.polygon.io/v2/aggs/ticker/$symbol/prev?adjusted=true&apiKey=" . API_KEY;
        $responseLastClose = file_get_contents($lastCloseUrl);
        if ($responseLastClose === false) {
            throw new Exception("Error fetching last close data for $symbol");
        }
        $lastCloseData = json_decode($responseLastClose, true);
        $lastClosePrice = $lastCloseData['results'][0]['c'] ?? null;

        // Fetch all-time high
        $allTimeHighUrl = "https://api.polygon.io/v2/aggs/ticker/$symbol/range/1/day/2010-01-01/2024-12-31?adjusted=true&apiKey=" . API_KEY;
        $responseAllTimeHigh = file_get_contents($allTimeHighUrl);
        if ($responseAllTimeHigh === false) {
            throw new Exception("Error fetching all time high data for $symbol");
        }
        $allTimeHighData = json_decode($responseAllTimeHigh, true);
        $allTimeHighPrice = max(array_column($allTimeHighData['results'], 'h'));

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
        $updateStmt = $con->prepare("UPDATE efts SET last_close=?, all_time_high=?, market_cap=?, average_volume=?, correction_range=?, date_time=? WHERE symbl=?");
        if (!$updateStmt) {
            throw new Exception("Prepare statement failed: " . $con->error);
        }
        $updateStmt->bind_param("dddidss", $lastClosePrice, $allTimeHighPrice, $marketCap, $averageVolume, $correctionRangeFormatted, $dateTime, $symbol);
        if (!$updateStmt->execute()) {
            // If no rows updated (record doesn't exist), insert new record
            if ($updateStmt->errno === 0 && $updateStmt->affected_rows === 0) {
                $insertStmt = $con->prepare("INSERT INTO efts (symbl, name, last_close, all_time_high, market_cap, average_volume, correction_range, date_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
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
