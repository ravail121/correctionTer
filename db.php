<?php 
$con = new mysqli('localhost', 'corrgltt_companies', '?%!!mZ5HP^#-', 'corrgltt_companies');
//$con = new mysqli('localhost', 'root', '', 'corrgltt_companies');
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>