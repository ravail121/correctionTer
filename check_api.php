<?php

// Function to get data from the Polygon.io API
function fetchStockData($ticker, $startDate, $endDate, $apiKey) {
    $url = "https://api.polygon.io/v2/aggs/ticker/$ticker/range/1/day/$startDate/$endDate?adjusted=true&sort=asc&apiKey=$apiKey";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Function to calculate how many times the stock hit a new all-time high
function calculateAllTimeHighs($results) {
    $allTimeHigh = 0;
    $allTimeHighCount = 0;

    foreach ($results as $dayData) {
        $highPrice = $dayData['h'];
        if ($highPrice > $allTimeHigh) {
            $allTimeHigh = $highPrice;
            $allTimeHighCount++;
        }
    }

    return $allTimeHighCount;
}

// Replace with your actual API key
$apiKey = "zq_ujf8gikb5aQgmyimpTQ7CIIpN5x2x";

// Get inputs for the ticker symbol and date range
$ticker = "WMT";
$startDate ="2018-01-09";
$endDate = "2023-02-10";
echo "https://api.polygon.io/v2/aggs/ticker/$ticker/range/1/day/$startDate/$endDate?adjusted=true&sort=asc&apiKey=$apiKey"."<br/>";
// Fetch data from API
$data = fetchStockData($ticker, $startDate, $endDate, $apiKey);

// Check if the data fetch was successful
if (isset($data['results'])) {
    $results = $data['results'];
    $allTimeHighCount = calculateAllTimeHighs($results);
    echo "The company $ticker hit an all-time high $allTimeHighCount times between $startDate and $endDate.\n";
} else {
    echo "Failed to fetch data or no data available for the given date range and ticker.\n";
}

?>
