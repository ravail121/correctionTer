<?php 
$con = new mysqli('localhost', 'corrgltt_companies', '?%!!mZ5HP^#-', 'corrgltt_companies');
//$con = new mysqli('localhost', 'root', '', 'corrgltt_companies');
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}



// Function to get data from the Polygon.io API
function fetchStockData($ticker, $startDate, $endDate, $apiKey) {
    $url = "https://api.polygon.io/v2/aggs/ticker/$ticker/range/1/day/$startDate/$endDate?adjusted=true&sort=asc&apiKey=$apiKey";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Function to calculate how many times the stock hit a new all-time high and the time since the last ATH
function calculateAllTimeHighs($results) {
    $allTimeHigh = 0;
    $allTimeHighCount = 0;
    $lastATHDate = null;

    foreach ($results as $dayData) {
        $highPrice = $dayData['h'];
        $currentDate = $dayData['t']; // Timestamp of the current data point

        if ($highPrice > $allTimeHigh) {
            $allTimeHigh = $highPrice;
            $allTimeHighCount++;
            $lastATHDate = $currentDate; // Update the last ATH date to the current date
        }
    }

    return [$allTimeHighCount, $lastATHDate];
}

// Replace with your actual API key
$apiKey = "zq_ujf8gikb5aQgmyimpTQ7CIIpN5x2x";

// Get today's date and the date from 5 years ago
$endDate = (new DateTime())->format('Y-m-d'); // Today's date
$startDate = (new DateTime())->modify('-5 years')->format('Y-m-d'); // Date 5 years ago







$categories_query = mysqli_query($con, "SELECT * FROM companies");
 while ($fetch_categories = mysqli_fetch_array($categories_query)) {
            $symbl = $fetch_categories["symbl"];
            
 
            // Get inputs for the ticker symbol
            $ticker =$symbl;

            // Fetch data from API
            $data = fetchStockData($ticker, $startDate, $endDate, $apiKey);

            // Check if the data fetch was successful
            if (isset($data['results'])) {
                $results = $data['results'];
                list($allTimeHighCount, $lastATHDate) = calculateAllTimeHighs($results);


                if ($lastATHDate !== null) {
                    $lastATHDateFormatted = date('Y-m-d', $lastATHDate / 1000); // Convert timestamp to date
                    $currentDate = new DateTime();
                    $lastATHDateObj = new DateTime($lastATHDateFormatted);
                    $interval = $currentDate->diff($lastATHDateObj);
                    $days=$interval->days;
                    $updateQuery = "UPDATE companies SET 
                                    all_time_high_count = '$allTimeHighCount',
                                    ath_since = '$days'
                                    WHERE symbl = '$ticker'";
                    if ($con->query($updateQuery) === TRUE) {
                        echo "Record updated successfully";
                    } else {
                        echo "Error updating record: " . $con->error.",<br/>";
                    }



                }

            } 






        }

        ?>
