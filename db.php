<?php
// Allow local/dev to override DB settings via environment variables (Docker, etc.)
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'corrgltt_companies';
$db_pass = getenv('DB_PASS') ?: '?%!!mZ5HP^#-';
$db_name = getenv('DB_NAME') ?: 'corrgltt_companies';

$con = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>