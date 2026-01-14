<?php
$con = new mysqli('localhost', 'corrgltt_companies', '?%!!mZ5HP^#-', 'corrgltt_companies');
date_default_timezone_set('America/New_York');
$currentDateTime = date('D M d Y h:i:s A');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$symbl = $_POST['symbl'];
$name = $_POST['name'];
$last_close = $_POST['last_close'];
$all_time_high = $_POST['all_time_high'];
$market_cap = $_POST['market_cap'];
$average_volume = $_POST['average_volume'];
$correction_range = $_POST['correction_range'];

$checkQuery = "SELECT * FROM companies WHERE symbl = '$symbl'";
$result = $con->query($checkQuery);

if ($result->num_rows > 0) {
    // Symbol exists, update the record
    $updateQuery = "UPDATE companies SET  
                    last_close = '$last_close', 
                    all_time_high = '$all_time_high', 
                    market_cap = '$market_cap', 
                    average_volume = '$average_volume', 
                    correction_range = '$correction_range',
                    date_time='$currentDateTime'
                    WHERE symbl = '$symbl'";
    
    if ($con->query($updateQuery) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $con->error;
    }
} else {
    // Symbol does not exist, insert a new record
    $insertQuery = "INSERT INTO companies (symbl, name, last_close, all_time_high, market_cap, average_volume, correction_range,date_time) VALUES ('$symbl', '$name', '$last_close', '$all_time_high', '$market_cap', '$average_volume', '$correction_range','$currentDateTime')";
    
    if ($con->query($insertQuery) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $insertQuery . "<br>" . $con->error;
    }
}

$con->close();
?>
