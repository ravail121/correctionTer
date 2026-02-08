<?php

function getDbConnection() {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    return $con;
}

function fetchCompanyData($symbol) {
    $apiKey = API_KEY;
    $url = "https://api.polygon.io/v1/meta/symbols/$symbol/company?apiKey=$apiKey";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function formatCorrectionRange($range) {
    return ($range >= 0) ? '+' . number_format($range, 2) . '%' : number_format($range, 2) . '%';
}

function getTradingDate() {
    // Set timezone to New York
    date_default_timezone_set('America/New_York');
    
    // Get current date and time in New York
    $now = new DateTime('now', new DateTimeZone('America/New_York'));
    $currentTime = $now->format('H:i');
    $currentDate = $now->format('Y-m-d');
    $dayOfWeek = $now->format('N'); // 1 (Monday) to 7 (Sunday)
    
    // If time is before 9:30 AM or it's a weekend, use previous trading day
    if ($currentTime < '09:30' || $dayOfWeek >= 6) {
        // Get previous trading day (skip weekends)
        $date = clone $now;
        $date->modify('-1 day');
        
        // Skip weekends - go back to Friday if it's Saturday or Sunday
        while ($date->format('N') >= 6) { // Saturday (6) or Sunday (7)
            $date->modify('-1 day');
        }
        
        return $date->format('Y-m-d');
    } else {
        // Market is open, use current date
        return $currentDate;
    }
}

function getPreviousRangeDate($lastTradingDay) {
    // Go 7 days back to ensure we cover 5 trading days even with weekends/holidays
    $ny = new DateTimeZone('America/New_York');
    $date = new DateTime($lastTradingDay, $ny);
    $date->modify('-7 days');
    return $date->format('Y-m-d');
}



function getStartDateForAverageVolume($endDate, $tradingDays = 10) {
    // Calculate start date by going back approximately 14-15 calendar days
    // to account for weekends (10 trading days = ~14 calendar days)
    $end = new DateTime($endDate);
    $start = clone $end;
    $start->modify('-' . (int)($tradingDays * 1.4) . ' days'); // Go back enough days to get 10 trading days
    
    return $start->format('Y-m-d');
}

function processCompanies($companies, $con, $errorTracker, $date) {
    // Skip processing for these symbols
    $skipSymbols = ['IPG', 'WBA', 'FI', 'PARA', 'K'];
    
    foreach ($companies as $symbol => $name) {
        // Skip processing if symbol is in skip list
        if (in_array($symbol, $skipSymbols)) {
            continue;
        }
        $missingFields = [];
        $hasError = false;
        $errorMessage = '';
        
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
                $missingFields[] = 'Current Price';
                throw new Exception("Unable to retrieve last close price for $symbol");
            }

            // Fetch all-time high
            $allTimeHighUrl = "https://api.polygon.io/v2/aggs/ticker/$symbol/range/1/day/2010-01-01/2027-12-31?adjusted=true&apiKey=" . API_KEY;
            $responseAllTimeHigh = file_get_contents($allTimeHighUrl);
            if ($responseAllTimeHigh === false) {
                throw new Exception("Error fetching all time high data for $symbol");
            }
            $allTimeHighData = json_decode($responseAllTimeHigh, true);
            if (empty($allTimeHighData['results'])) {
                $missingFields[] = 'All Time High';
                throw new Exception("No all-time high data returned for $symbol");
            }
            $allTimeHighPrice = max(array_column($allTimeHighData['results'], 'h'));

            // Calculate correction range (guard against division by zero)
            $correctionRange = ($allTimeHighPrice != 0 && is_numeric($allTimeHighPrice))
                ? ($lastClosePrice - $allTimeHighPrice) / $allTimeHighPrice * 100
                : 0;
            $correctionRangeFormatted = formatCorrectionRange($correctionRange);

            // Fetch market cap
            $companyUrl = "https://api.polygon.io/v3/reference/tickers/$symbol?apiKey=" . API_KEY;
            $responseCompany = file_get_contents($companyUrl);
            if ($responseCompany === false) {
                throw new Exception("Error fetching company data for $symbol");
            }
            $companyData = json_decode($responseCompany, true);
            $marketCap = $companyData['results']['market_cap'] ?? null;
            
            // Special case for MSFT
            if ($symbol == 'MSFT') {
                $marketCap = '3180000000000';
            }
            
            if ($marketCap === null && $symbol != 'MSFT') {
                $missingFields[] = 'Market Cap';
            }

            // Fetch average volume from last 10 trading days
            $startDate = getStartDateForAverageVolume($date, 10);
            $volumeUrl = "https://api.polygon.io/v2/aggs/ticker/$symbol/range/1/day/$startDate/$date?adjusted=true&apiKey=" . API_KEY;
            $responseVolume = file_get_contents($volumeUrl);
            if ($responseVolume === false) {
                throw new Exception("Error fetching volume data for $symbol");
            }
            $volumeData = json_decode($responseVolume, true);
            if (empty($volumeData['results'])) {
                $missingFields[] = 'Average Volume';
                $averageVolume = 0;
            } else {
                // Get volumes from results and take the last 10 trading days
                $volumes = array_column($volumeData['results'], 'v');
                $volumes = array_slice($volumes, -10); // Get last 10 trading days
                if (count($volumes) > 0) {
                    $averageVolume = array_sum($volumes) / count($volumes);
                } else {
                    $missingFields[] = 'Average Volume';
                    $averageVolume = 0;
                }
            }

            // Fetch EPS (Earnings Per Share) from last 4 quarters
            $epsValue = null;
            $financialsUrl = "https://api.polygon.io/vX/reference/financials?ticker=$symbol&timeframe=quarterly&apiKey=" . API_KEY;
            $responseFinancials = @file_get_contents($financialsUrl);
            if ($responseFinancials !== false) {
                $financialsData = json_decode($responseFinancials, true);
                if (!empty($financialsData['results']) && is_array($financialsData['results'])) {
                    // Get EPS from last 4 quarters
                    $epsQ4 = null;
                    $epsQ3 = null;
                    $epsQ2 = null;
                    $epsQ1 = null;
                    
                    // Get EPS from each of the last 4 quarters (results are ordered by most recent first)
                    if (count($financialsData['results']) > 0) {
                        $epsQ4 = $financialsData['results'][0]['financials']['income_statement']['basic_earnings_per_share']['value'] ?? null;
                    }
                    if (count($financialsData['results']) > 1) {
                        $epsQ3 = $financialsData['results'][1]['financials']['income_statement']['basic_earnings_per_share']['value'] ?? null;
                    }
                    if (count($financialsData['results']) > 2) {
                        $epsQ2 = $financialsData['results'][2]['financials']['income_statement']['basic_earnings_per_share']['value'] ?? null;
                    }
                    if (count($financialsData['results']) > 3) {
                        $epsQ1 = $financialsData['results'][3]['financials']['income_statement']['basic_earnings_per_share']['value'] ?? null;
                    }
                    
                    // Sum all available quarters to get annual EPS
                    $epsValue = 0;
                    $quartersCount = 0;
                    if ($epsQ4 !== null) {
                        $epsValue += $epsQ4;
                        $quartersCount++;
                    }
                    if ($epsQ3 !== null) {
                        $epsValue += $epsQ3;
                        $quartersCount++;
                    }
                    if ($epsQ2 !== null) {
                        $epsValue += $epsQ2;
                        $quartersCount++;
                    }
                    if ($epsQ1 !== null) {
                        $epsValue += $epsQ1;
                        $quartersCount++;
                    }
                    
                    // Only use EPS if we have at least some quarters of data
                    if ($quartersCount == 0) {
                        $epsValue = null;
                    } else {
                        // Format EPS to 2 decimal places
                        $epsValue = number_format($epsValue, 2, '.', '');
                    }
                }
            }
            
            // If EPS fetch failed, it's not critical - we'll just leave it as empty string
            // and won't add it to missingFields
            if ($epsValue === null) {
                $epsValue = '';
            }

            // Attempt to update existing record; if no rows affected, then insert new record
            $dateTime = date('Y-m-d H:i:s');
            
            // $snapshotUrl = "https://api.polygon.io/v2/snapshot/locale/us/markets/stocks/tickers/$symbol?apiKey=" . API_KEY;
            // $responseSnapshot = file_get_contents($snapshotUrl);
           
            // if ($responseSnapshot === false) {
            //     throw new Exception("Error fetching snapshot data for $symbol");
            // }
            // $snapshotData = json_decode($responseSnapshot, true);
            // $todaysChangePerc = $snapshotData['ticker']['todaysChangePerc'] ?? null;

            // ------------------------------------------------------
            // FETCH TWO DAYS OF CANDLES TO CALCULATE CHANGE %
            // ------------------------------------------------------


            $lastTradingDay = getTradingDate();                 // e.g. 2025-11-21
            $rangeStart = getPreviousRangeDate($lastTradingDay); // e.g. 2025-11-14
            
            $url = "https://api.polygon.io/v2/aggs/ticker/$symbol/range/1/day/$rangeStart/$lastTradingDay?adjusted=true&apiKey=" . API_KEY;
            
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            
            if (empty($data['results']) || count($data['results']) < 2) {
                throw new Exception("Not enough trading candles returned for $symbol");
            }
            
            // ALWAYS take LAST TWO candles
            $lastTwo = array_slice($data['results'], -2);
            
            $previousClose = $lastTwo[0]['c'];  // 266.25 (20 Nov)
            $lastClose = $lastTwo[1]['c'];      // 271.49 (21 Nov)
            
            $todaysChangePerc = ($previousClose != 0 && is_numeric($previousClose))
                ? number_format((($lastClose - $previousClose) / $previousClose) * 100, 2)
                : 'N/A';

            // Convert todaysChangePerc to string if it's not null
            if ($todaysChangePerc !== null) {
                $todaysChangePerc = number_format($todaysChangePerc, 2);
            } else {
                $todaysChangePerc = 'N/A'; // Handle null cases if API doesn't return data
                $missingFields[] = 'Change %';
            }
            // $updateStmt = $con->prepare("UPDATE companies SET last_close=?, all_time_high=?, market_cap=?, average_volume=?, correction_range=?, date_time=?,todaysChangePerc=?, eps_value=? WHERE symbl=?");
            // if (!$updateStmt) {
            //     throw new Exception("Prepare statement failed: " . $con->error);
            // }
            
            // $updateStmt->bind_param("dddidssss", $lastClosePrice, $allTimeHighPrice, $marketCap, $averageVolume, $correctionRangeFormatted, $dateTime, $todaysChangePerc, $epsValue, $symbol);
            // if (!$updateStmt->execute()) {
            //     // If no rows updated (record doesn't exist), insert new record
            //     if ($updateStmt->errno === 0 && $updateStmt->affected_rows === 0) {
            //         $insertStmt = $con->prepare("INSERT INTO companies (symbl, name, last_close, all_time_high, market_cap, average_volume, correction_range, date_time, todaysChangePerc, eps_value) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            //         if (!$insertStmt) {
            //             throw new Exception("Prepare statement failed: " . $con->error);
            //         }
            //         $insertStmt->bind_param("ssdddidsss", $symbol, $name, $lastClosePrice, $allTimeHighPrice, $marketCap, $averageVolume, $correctionRangeFormatted, $dateTime, $todaysChangePerc, $epsValue);
            //         if (!$insertStmt->execute()) {
            //             throw new Exception("Execute failed: " . $insertStmt->error);
            //         }
            //         echo "Record for $name inserted successfully.\n";
            //         $insertStmt->close();
            //     } else {
            //         throw new Exception("Execute failed: " . $updateStmt->error);
            //     }
            // } else {
            //     echo "Record for $name updated successfully.\n";
            // }
            // $updateStmt->close();
            
            
            // Step 1: check if symbol exists
$checkStmt = $con->prepare("SELECT 1 FROM companies WHERE symbl = ? LIMIT 1");
if (!$checkStmt) {
    throw new Exception("Prepare failed: " . $con->error);
}
$checkStmt->bind_param("s", $symbol);
$checkStmt->execute();
$checkStmt->store_result();
$symbolExists = $checkStmt->num_rows > 0;
$checkStmt->close();

// Step 2: update if exists
if ($symbolExists) {

    $updateStmt = $con->prepare(
        "UPDATE companies 
         SET last_close = ?, 
             all_time_high = ?, 
             market_cap = ?, 
             average_volume = ?, 
             correction_range = ?, 
             date_time = ?, 
             todaysChangePerc = ?, 
             eps_value = ?
         WHERE symbl = ?"
    );

    if (!$updateStmt) {
        throw new Exception("Prepare failed: " . $con->error);
    }

    $updateStmt->bind_param(
        "dddidssss",
        $lastClosePrice,
        $allTimeHighPrice,
        $marketCap,
        $averageVolume,
        $correctionRangeFormatted,
        $dateTime,
        $todaysChangePerc,
        $epsValue,
        $symbol
    );

    if (!$updateStmt->execute()) {
        throw new Exception("Update failed: " . $updateStmt->error);
    }

    $updateStmt->close();

} else {

    // Step 3: insert if not exists
    $insertStmt = $con->prepare(
        "INSERT INTO companies
        (symbl, name, last_close, all_time_high, market_cap, average_volume, correction_range, date_time, todaysChangePerc, eps_value)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    if (!$insertStmt) {
        throw new Exception("Prepare failed: " . $con->error);
    }

    $insertStmt->bind_param(
        "ssdddidsss",
        $symbol,
        $name,
        $lastClosePrice,
        $allTimeHighPrice,
        $marketCap,
        $averageVolume,
        $correctionRangeFormatted,
        $dateTime,
        $todaysChangePerc,
        $epsValue
    );

    if (!$insertStmt->execute()) {
        throw new Exception("Insert failed: " . $insertStmt->error);
    }

    $insertStmt->close();
}

// $checkStmt = $con->prepare("SELECT id FROM companies WHERE symbl = ?");
// if (!$checkStmt) {
//     throw new Exception("Prepare failed: " . $con->error);
// }

// $checkStmt->bind_param("s", $symbol);
// $checkStmt->execute();
// $checkStmt->store_result();

// $symbolExists = $checkStmt->num_rows > 0;
// $checkStmt->close();

// /*
// |--------------------------------------------------------------------------
// | 2. UPDATE if exists
// |--------------------------------------------------------------------------
// */
// if ($symbolExists) {

//     $updateStmt = $con->prepare("
//         UPDATE companies 
//         SET 
//             last_close = ?, 
//             all_time_high = ?, 
//             market_cap = ?, 
//             average_volume = ?, 
//             correction_range = ?, 
//             date_time = ?, 
//             todaysChangePerc = ?, 
//             eps_value = ?
//         WHERE symbl = ?
//     ");

//     if (!$updateStmt) {
//         throw new Exception("Prepare failed: " . $con->error);
//     }

//     $updateStmt->bind_param(
//         "ddddsssss",
//         $lastClosePrice,
//         $allTimeHighPrice,
//         $marketCap,
//         $averageVolume,
//         $correctionRangeFormatted,
//         $dateTime,
//         $todaysChangePerc,
//         $epsValue,
//         $symbol
//     );

//     if (!$updateStmt->execute()) {
//         throw new Exception("Update failed: " . $updateStmt->error);
//     }

//     echo "UPDATED: $symbol ($name)\n";
//     $updateStmt->close();

// /*
// |--------------------------------------------------------------------------
// | 3. INSERT if not exists
// |--------------------------------------------------------------------------
// */
// } else {

//     $insertStmt = $con->prepare("
//         INSERT INTO companies 
//         (symbl, name, last_close, all_time_high, market_cap, average_volume, correction_range, date_time, todaysChangePerc, eps_value)
//         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
//     ");

//     if (!$insertStmt) {
//         throw new Exception("Prepare failed: " . $con->error);
//     }

//     $insertStmt->bind_param(
//         "ssddddssss",
//         $symbol,
//         $name,
//         $lastClosePrice,
//         $allTimeHighPrice,
//         $marketCap,
//         $averageVolume,
//         $correctionRangeFormatted,
//         $dateTime,
//         $todaysChangePerc,
//         $epsValue
//     );

//     if (!$insertStmt->execute()) {
//         throw new Exception("Insert failed: " . $insertStmt->error);
//     }

//     echo "INSERTED: $symbol ($name)\n";
//     $insertStmt->close();
// }
            
            // Record success if we got here without exception
            if (empty($missingFields)) {
                $errorTracker->recordSuccess($symbol);
            } else {
                // Partial success - some fields missing but data saved
                $errorTracker->recordError($symbol, $name, "Partial data - missing fields: " . implode(', ', $missingFields), $missingFields);
            }
            
        } catch (Exception $e) {
            $hasError = true;
            $errorMessage = $e->getMessage();
            echo "Error processing $name: " . $errorMessage . "\n";
            $errorTracker->recordError($symbol, $name, $errorMessage, $missingFields);
        }
    }
}

function getCompaniesChunk($con, $chunkIndex, $totalChunks = 6)
{
    $companies = [];

    $query = "
        SELECT symbol, company_name 
        FROM sp500
        WHERE MOD(id, $totalChunks) = $chunkIndex
        ORDER BY id ASC
    ";
    // echo $query;exit;
    $result = $con->query($query);

    if (!$result) {
        die('Error fetching companies: ' . $con->error);
    }

    while ($row = $result->fetch_assoc()) {
        $companies[$row['symbol']] = $row['company_name'];
    }

    return $companies;
}


function processCryptocurrencies($cryptocurrencies, $conn, $errorTracker) {
    // Polygon API URL base and your API key
    $apiKey = API_KEY;
    $baseUrl = "https://api.polygon.io/v2/aggs/ticker/X:";
    
    // Date range for the query
    $startDate = date('Y-m-d', strtotime('-2 years'));
    $endDate = date('Y-m-d', strtotime('-1 day'));
    
    $totalCryptos = count($cryptocurrencies);
    
    // Iterate over each cryptocurrency
    foreach ($cryptocurrencies as $crypto) {
        $symbol = $crypto['symbol'];
        $name = $crypto['name'];
        $missingFields = [];
        $url = "{$baseUrl}{$symbol}USD/range/1/day/{$startDate}/{$endDate}?adjusted=true&sort=asc&apiKey={$apiKey}";
        
        // Initialize a cURL session
        $ch = curl_init();
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Execute cURL request
        $response = curl_exec($ch);
        
        // Check for cURL errors
        if ($response === false) {
            $errorTracker->recordError($symbol, $name, "Polygon request failed: " . curl_error($ch));
            curl_close($ch);
            continue; // Skip this cryptocurrency if an error occurs
        }
        
        // Close cURL session
        curl_close($ch);
        
        // Decode JSON response
        $data = json_decode($response, true);
        
        // Initialize variables to store the last close price and the all-time high
        $lastClosePrice = null;
        $allTimeHigh = null;
        
        // Check if data is available
        if (isset($data['results']) && is_array($data['results']) && !empty($data['results'])) {
            $results = $data['results'];
            
            // Get the last close price
            $lastResult = end($results);
            $lastClosePrice = $lastResult['c'] ?? null;
            if ($lastClosePrice === null) {
                $missingFields[] = 'Last Close';
            }
            
            // Calculate the all-time high
            foreach ($results as $dayData) {
                if (!isset($dayData['h'])) {
                    continue;
                }
                if ($allTimeHigh === null || $dayData['h'] > $allTimeHigh) {
                    $allTimeHigh = $dayData['h'];
                }
            }
            if ($allTimeHigh === null) {
                $missingFields[] = 'All-Time High';
            }
            
            // Calculate the correction range as a percentage
            $correctionRange = ($allTimeHigh > 0 && $lastClosePrice !== null)
                ? (($allTimeHigh - $lastClosePrice) / $allTimeHigh) * 100
                : null;
            if ($correctionRange === null) {
                $missingFields[] = 'Correction Range';
            }
            
            if (!empty($missingFields)) {
                $errorTracker->recordError($symbol, $name, 'Missing fields: ' . implode(', ', $missingFields), $missingFields);
                continue;
            }
            
            // Check if the crypto already exists
            $selectStmt = $conn->prepare("
                SELECT 1
                FROM crypto
                WHERE symbol = ?
                LIMIT 1
            ");
            
            if ($selectStmt) {
                $selectStmt->bind_param('s', $symbol);
                $selectStmt->execute();
                $selectStmt->store_result();
                $exists = $selectStmt->num_rows > 0;
                $selectStmt->close();
                
                if ($exists) {
                    $stmt = $conn->prepare("
                        UPDATE crypto
                        SET last_close_price = ?, all_time_high = ?, correction_range = ?
                        WHERE symbol = ?
                    ");
                    
                    if ($stmt) {
                        $stmt->bind_param('ddds', $lastClosePrice, $allTimeHigh, $correctionRange, $symbol);
                        if (!$stmt->execute()) {
                            $errorTracker->recordError($symbol, $name, 'DB update failed: ' . $stmt->error);
                            $stmt->close();
                            continue;
                        }
                        $stmt->close();
                    } else {
                        $errorTracker->recordError($symbol, $name, 'DB update prepare failed: ' . $conn->error);
                        continue;
                    }
                } else {
                    $stmt = $conn->prepare("
                        INSERT INTO crypto (name, symbol, last_close_price, all_time_high, correction_range)
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    
                    if ($stmt) {
                        $stmt->bind_param('ssddd', $name, $symbol, $lastClosePrice, $allTimeHigh, $correctionRange);
                        if (!$stmt->execute()) {
                            $errorTracker->recordError($symbol, $name, 'DB insert failed: ' . $stmt->error);
                            $stmt->close();
                            continue;
                        }
                        $stmt->close();
                    } else {
                        $errorTracker->recordError($symbol, $name, 'DB insert prepare failed: ' . $conn->error);
                        continue;
                    }
                }
                $errorTracker->recordSuccess($symbol);
            } else {
                $errorTracker->recordError($symbol, $name, 'DB lookup prepare failed: ' . $conn->error);
            }
            
        } else {
            $errorTracker->recordError($symbol, $name, "Polygon returned no candle data for {$startDate} to {$endDate}");
        }
    }
    
    // Errors are now saved to database only - no email sending from crypto scripts
    // Email will be sent by nightly script (send_stock_error_email.php)
}

function processIndexes($indexes, $conn, $errorTracker) {
    // Date range: start = -2 years, end = -1 day
    $startDate = date('Y-m-d', strtotime('-2 years'));
    $endDate = date('Y-m-d', strtotime('-1 day'));
    
    $totalIndexes = count($indexes);
    
    // Iterate over each index
    foreach ($indexes as $index) {
        $symbol = $index['symbol'];
        $name = $index['name'];
        $missingFields = [];
        
        // Construct API URL for index (using I: prefix for indexes)
        $url = "https://api.polygon.io/v2/aggs/ticker/I:$symbol/range/1/day/$startDate/$endDate?adjusted=true&sort=asc&apiKey=" . API_KEY;
        
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        
        // Check for cURL errors
        if ($response === false) {
            $errorTracker->recordError($symbol, $name, "Polygon request failed: " . curl_error($ch));
            curl_close($ch);
            continue;
        }
        
        curl_close($ch);
        
        // Decode JSON response
        $data = json_decode($response, true);
        
        // Initialize variables
        $lastClosePrice = null;
        $allTimeHigh = null;
        
        // Check if data is available
        if (isset($data['results']) && is_array($data['results']) && !empty($data['results'])) {
            $results = $data['results'];
            
            // Get the last close price
            $lastResult = end($results);
            $lastClosePrice = $lastResult['c'] ?? null;
            if ($lastClosePrice === null) {
                $missingFields[] = 'Last Close';
            }
            
            // Calculate the all-time high
            foreach ($results as $dayData) {
                if (!isset($dayData['h'])) {
                    continue;
                }
                if ($allTimeHigh === null || $dayData['h'] > $allTimeHigh) {
                    $allTimeHigh = $dayData['h'];
                }
            }
            if ($allTimeHigh === null) {
                $missingFields[] = 'All-Time High';
            }
            
            if (!empty($missingFields)) {
                $errorTracker->recordError($symbol, $name, 'Missing fields: ' . implode(', ', $missingFields), $missingFields);
                continue;
            }
            
            // Check if the index already exists
            $selectStmt = $conn->prepare("
                SELECT 1
                FROM indexes
                WHERE symbl = ?
                LIMIT 1
            ");
            
            if ($selectStmt) {
                $selectStmt->bind_param('s', $symbol);
                $selectStmt->execute();
                $selectStmt->store_result();
                $exists = $selectStmt->num_rows > 0;
                $selectStmt->close();
                
                if ($exists) {
                    // Update existing record
                    $link = $index['link'] ?? '';
                    $stmt = $conn->prepare("
                        UPDATE indexes
                        SET last_close = ?, all_time_high = ?, date_time = ?, link = ?
                        WHERE symbl = ?
                    ");
                    
                    if ($stmt) {
                        $dateTime = date('Y-m-d H:i:s');
                        $stmt->bind_param('ddsss', $lastClosePrice, $allTimeHigh, $dateTime, $link, $symbol);
                        if (!$stmt->execute()) {
                            $errorTracker->recordError($symbol, $name, 'DB update failed: ' . $stmt->error);
                            $stmt->close();
                            continue;
                        }
                        $stmt->close();
                    } else {
                        $errorTracker->recordError($symbol, $name, 'DB update prepare failed: ' . $conn->error);
                        continue;
                    }
                } else {
                    // Insert new record
                    $link = $index['link'] ?? '';
                    $stmt = $conn->prepare("
                        INSERT INTO indexes (symbl, name, last_close, all_time_high, date_time, link)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    
                    if ($stmt) {
                        $dateTime = date('Y-m-d H:i:s');
                        $stmt->bind_param('ssddss', $symbol, $name, $lastClosePrice, $allTimeHigh, $dateTime, $link);
                        if (!$stmt->execute()) {
                            $errorTracker->recordError($symbol, $name, 'DB insert failed: ' . $stmt->error);
                            $stmt->close();
                            continue;
                        }
                        $stmt->close();
                    } else {
                        $errorTracker->recordError($symbol, $name, 'DB insert prepare failed: ' . $conn->error);
                        continue;
                    }
                }
                $errorTracker->recordSuccess($symbol);
            } else {
                $errorTracker->recordError($symbol, $name, 'DB lookup prepare failed: ' . $conn->error);
            }
            
        } else {
            $errorTracker->recordError($symbol, $name, "Polygon returned no candle data for {$startDate} to {$endDate}");
        }
    }
    
    // Errors are now saved to database only - no email sending from index scripts
    // Email will be sent by nightly script (send_stock_error_email.php)
}
?>
